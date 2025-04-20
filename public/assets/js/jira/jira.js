class Usuario {
    constructor(usuario) {
        this.id = usuario.accountId,
        this.nome = usuario.displayName,
        this.avatar = usuario.avatarUrls['48x48'];
    }
}

class Status {
    constructor(status) {
        this.id = status.id;
        this.nome = status.name;
    }
}

class Tipo {
    constructor(tipo) {
        this.id = tipo.id;
        this.nome = tipo.name;
        this.icone = tipo.iconUrl;
    }
}

class Prioridade {
    constructor(prioridade) {
        this.id = prioridade.id;
        this.nome = prioridade.name;
        this.icone = prioridade.iconUrl;
    }
}

class Comentario {  
    constructor(comentario) {
        this.autor = new Usuario(comentario.author);
        this.texto = comentario.body;
        this.dataCriacao = comentario.created;
    }
}

class Componente {
    constructor(componente) {
        this.id = componente.id;
        this.nome = componente.name;
    }
}

class RegistroTrabalho {

    constructor(registroTrabalho) {
        let horas = registroTrabalho.timeSpentSeconds;
        this.autor = new Usuario(registroTrabalho.author);
        this.comentario = registroTrabalho.comment;
        this.dataInicio = registroTrabalho.started;
        let dataMatch = registroTrabalho.started.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/);
        this.dataInicioYmd = dataMatch[0]??null;
        this.horas = horas > 0 ? parseFloat(horas/3600).toFixed(1) : 0;
    }
}

class Projeto {

}

class Painel {
    constructor(painel) {
        this.id = painel.id;
        this.nome = painel.name;
    }
}

class Sprint{

    constructor(sprint, painel) {
        this.id = sprint.id;
        this.nome = sprint.name;
        this.objetivo = sprint.goal;
        this.painel = new Painel(painel);
        this.dataInicio = sprint.startDate;
        this.dataFim = sprint.endDate;
        this.status = sprint.state;
        this.tarefas = [];
        this.responsaveis = [];
        this.totalWorklog = 0; // TODO mudar totalHorasRegistroSprint;
        this.registroTrabalhoSprint = (new RegistroTrabalhoSemanal(sprint.startDate, sprint.endDate)).getRegistroTrabalhoSprint();
        this.contadorTarefas = new ContadorTarefa();      
    }

    setTarefa(tarefas, direcaoOrdenacao) {
        tarefas.forEach((t) => {
            let tarefa = new Tarefa(t);
            let responsavel = tarefa.responsavel ? tarefa.responsavel : { id: '3085488b-fc05-4bce-9f40-0affc57f4994', nome: 'Não atribuido', avatarUrls: {"48x48":null } }
            if(this.responsaveis.findIndex((usuario) => usuario.id == responsavel.id) == -1) {
                this.responsaveis.push(responsavel);
            }
            this.tarefas.push(tarefa);
        });

        this.tarefas = this.ordenarTarefas(direcaoOrdenacao);
        this.responsaveis = this.ordenarResponsaveis(direcaoOrdenacao);
    }

    ordenarTarefas(direcaoOrdenacao) {
        return this.tarefas.sort((a, b) => {
                if (a.status.id > b.status.id) return -1;
                if (a.status.id < b.status.id) return 1;
            });
    }

    ordenarResponsaveis(direcaoOrdenacao) {
        return this.responsaveis.sort((a, b) => {
            if (a.nome < b.nome) return -1;
            if (a.nome > b.nome) return 1;
        });
    }
}

class Tarefa {

    constructor(tarefa) {

        let estimate = tarefa.fields.timetracking.timeSpentSeconds;
        let original_estimate = tarefa.fields.timetracking.originalEstimateSeconds;

        this.id = tarefa.key;
        this.resumo = tarefa.fields.summary,
        this.descricao = tarefa.fields.description;
        this.responsavel = tarefa.fields.assignee ? new Usuario(tarefa.fields.assignee) : null;
        this.relator = tarefa.fields.reporter ? new Usuario(tarefa.fields.reporter) : null;
        this.status = new Status(tarefa.fields.status);
        this.story_points_estimate = tarefa.fields.customfield_10016 ?? 0;
        this.story_points = tarefa.fields.customfield_10026 ?? 0;
        this.estimate = estimate > 0 ? parseFloat(estimate/3600).toFixed(1) : 0; // /3600
        this.original_estimate = original_estimate > 0 ? parseFloat(original_estimate/3600).toFixed(1) : 0; // /3600
        this.url = `https://otjira.atlassian.net/browse/${tarefa.key}`;
        this.setRegistroTrabalho(tarefa.fields.worklog.worklogs);
        this.setComentarios(tarefa.fields.coment ? tarefa.fields.coment.comments : []);
        this.setComponentes(tarefa.fields.components);
        this.isAtendimento = this.checkIsAtendimento();
        this.tipo = new Tipo(tarefa.fields.issuetype);
        this.prioridade = new Prioridade(tarefa.fields.priority);
    }

    setRegistroTrabalho(registrosTrabalhos) {
        this.registrosTrabalhos = [];
        if(registrosTrabalhos.length > 0) {
            registrosTrabalhos.forEach((registroTrabalho) => {
                this.registrosTrabalhos.push(new RegistroTrabalho(registroTrabalho));
            });
        }
    }

    setComentarios(comentarios) {
        this.comentarios = [];
        comentarios.forEach((comentario) => {
            this.comentarios.push(new Comentario(comentario));
        });
    }

    setComponentes(componentes) {
        this.componentes = [];
        componentes.forEach((componente) => {
            this.componentes.push(new Componente(componente));
        });
    }

    checkIsAtendimento() {
        let componente = this.componentes.find((componente) => componente.nome == 'OCTO - Atendimento');
        return componente != undefined;
    }
}

class ContadorTarefa {

    constructor() {
        this.qtdTarefas = 0;
        this.statuses = [];
        this.storyPointsZerado = 0;
        this.storyPointsEstimateZerado = 0;
        this.estimateZerado = 0;
        this.estimateOriginalZerado = 0;
    }

    compute(tarefas, responsavelId, statuses) {

        this.storyPointsZerado = 0;
        this.storyPointsEstimateZerado = 0;
        this.estimateZerado = 0;
        this.estimateOriginalZerado = 0;
        
        this.statuses = [];
        statuses.forEach((status) => {
            if(!this.statuses.find((st) => st.id == status.id)) {
                this.statuses.push({
                    id: status.id,
                    nome: status.nome,
                    qtd:0
                });
            }
        });

        if (tarefas.length > 0) {
            this.qtdTarefas = 0;
            
            if(responsavelId) {
                tarefas = tarefas
                    .filter((tarefa) => tarefa.responsavel && tarefa.responsavel.id == responsavelId)
            }

            tarefas.forEach((tarefa) => {
                if (tarefa.story_points_estimate == 0) this.storyPointsEstimateZerado++;
                if (tarefa.story_points == 0) this.storyPointsZerado++;
                if (tarefa.estimate == 0) this.estimateZerado++;
                if (tarefa.original_estimate == 0) this.estimateOriginalZerado++;
                this.qtdTarefas++;

                let status = this.statuses.find((status) => status.id == tarefa.status.id);
                if(status) {
                    status.qtd++;
                }
            });
        }
    }

    getContador() {
        return this;
    }
}

class RegistroTrabalhoSemanal {

    constructor(sprintStartDate, sprintEndDate) {
        this.iniciarRegistroTrabalhoSprint(sprintStartDate, sprintEndDate);
        this.totalWorklog = 0;
    }

    iniciarRegistroTrabalhoSprint(sprintStartDate, sprintEndDate) {
        let data = new Date(sprintStartDate);
        let contador = 0;
        let dataRegistroTrabalho = null;
        this.registroTrabalhoSprint = [];

        do {

            dataRegistroTrabalho = data.toISOString().replace(/([0-9]{4}-[0-9]{2}-[0-9]{2}).*/, '$1');

            data.setDate(data.getDate() + 1);

            if ([0, 1].includes(data.getDay())) {
                continue;
            }

            contador++;

            this.registroTrabalhoSprint.push({
                dia: this.getDiaSemana(data.getDay()),
                data: dataRegistroTrabalho, //.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, '$1$2$3')
                horas: 0
            });

        } while (dataRegistroTrabalho != sprintEndDate.replace(/([0-9]{4}-[0-9]{2}-[0-9]{2}).*/, '$1'));
    }

    getRegistroTrabalhoSprint() {
        return this.registroTrabalhoSprint;
    }

    setRegistroTrabalhoTarefa(tarefas, responsavelId) {
        tarefas
        // .filter((registroTrabalho) => registroTrabalho.autor.id == responsavelId)
        .forEach((tarefa) =>{ 
            tarefa.registrosTrabalhos
            .filter((registroTrabalho) => registroTrabalho.autor.id == responsavelId)
            .forEach((registroTrabalho) => {
                let index = this.registroTrabalhoSprint.findIndex((registro) => registro.data == registroTrabalho.dataInicioYmd);
                if (index != -1) {
                    this.registroTrabalhoSprint[index].horas += parseFloat(registroTrabalho.horas);
                    this.totalWorklog += parseFloat(registroTrabalho.horas)
                }
            });
         });
    }

    getDiaSemana(dia) {
        let diaSemana = '';
        switch (dia) {
            case 2:
                diaSemana = 'Seg';
                break;
            case 3:
                diaSemana = 'Ter';
                break;
            case 4:
                diaSemana = 'Qua';
                break;
            case 5:
                diaSemana = 'Qui';
                break;
            case 6:
                diaSemana = 'Sex';
                break;
        }
        return diaSemana;
    }
}