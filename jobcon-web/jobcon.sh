#!/bin/bash

declare -a projetos
dataAtual=$(date +"%Y-%m-%d %H:%m:%S")

# funcao que acessa um determinado container do octo
function accessContainer() {
    command="exec -it"
    help='octo -ac [container (ex: php, mongo, mysql)]'
    for container in "$2"; do
        case "$container" in
            php)
                command="$command php74"
                shift
                ;;
            mongo)
                command="$command -w /var/www/mongodumps mongo"
                shift
                ;;
            *)
                echo "$help"
                shift
                exit 0
                ;;                                            
        esac
    done

    command="$command bash"
    
    # echo "docker $command"

    echo "Acessando container $container"

    docker $command
}

# funcao que mostra todos os branches dos projetos do octo
function branches(){
    echo "#### Exibindo os branches do octo ####"

    projetos=('ott' 'api-cadastro-op' 'api-cnab' 'api-ocorrencia' 'api-processamento' 'api-criterios-elegibilidade' 'api-armazenamento' 'api-nosql' 'api-autorizacao' 'api-relatorios' 'api-auditoria')    

    for projeto in "${projetos[@]}"; do
        path="/home/newtoncosta/workspace/fintools/projetos/7.4/www/$projeto"
        cd $path
        branch=$(git branch --show-current)
        echo "$branch $projeto"
        # git fetch
        cd ../
    done    
}

# Limpar logs dos projetos
function clear() {

    echo "#### Limpando pasta de logs do octo ####"

    projetos=('ott' 'api-cadastro-op' 'api-cnab' 'api-ocorrencia' 'api-processamento' 'api-criterios-elegibilidade' 'api-armazenamento' 'api-nosql' 'api-autorizacao' 'api-relatorios' 'api-auditoria')

    for projeto in "${projetos[@]}"; do

        path="/home/newtoncosta/workspace/fintools/projetos/7.4/www/$projeto/storage/logs"

        arquivo="octolog-$data"

        if [ "$projeto" == "ott" ]; then
            arquivo="octo"
        fi

        log="$path/$projeto.log"
        octoLog="$path/$arquivo.log"

        echo "" > $log
        # echo "limpo $log"

        echo "" > $octoLog        
        # echo "limpo $octoLog"

        if [ -f "$octoLog" ]; then
            pathLog="$path/octo*"
            find $pathLog -mtime +1 -exec rm -rf "{}" \;
            # echo "find $pathLog -mtime 1 -exec rm -rf '{}' \;"
            echo "limpo $projeto arquivos antigos"
        fi

    done
    
    echo "#### Logs do octo limpos ####"
}

# Limpar o shared de arquivos do octo
function clearSharedOcto() {
    echo "#### Limpando pasta shared do octo ####"
    path="/home/newtoncosta/workspace/fintools/projetos/7.4/www/ott/storage/shared/octo/processamento"
    folder="$path/*"
    rm -rf $folder
    echo "#### Pasta shared do octo limpa ####"
}

function cursor() {
    command="exec -it -w /var/www/html/api-processamento php74 php artisan query:cursor"

    # echo "docker $command"

    docker $command  
}

# ajuda para a rodar os comandos
function help() {
    echo "Modo de utilização do comando:";
    echo "fincon [opcoes]"
    # echo "  -ac|--accessContainer acessa um container de servico do octo (ex: mongo, php74, web)"
    # echo "   -b|--branches exibe os branches do projeto (OCTO+)"
    # echo "   -c|--clear limpa os arquivos de logs dos storages dos projetos"
    # echo " -cso|--clearSharedOcto limpa as pastas compartilhadas do octo"
    # echo " -csr|--cursor executa query de cursor do octo"
    # echo "   -h|--help mostra a ajuda dos comandos"
    # echo "  -mi|--mongoImport [-a arquivo_1.json -c collection_foo] executa um import no mongo do arquivo enviado"
    # echo "   -q|--queue liga as filas de processamento"
    # echo "  -qc|--queueCriteria liga as filas de critérios"
    # echo "  -qr|--queue liga as filas de relatorios"
    echo "   -m|--migrate executa os comandos de migration do projeto"
	exit 1
}

# funcao que executa uma importacao de dados no mongo do docker
function mongoImport() {
    if [[ -z "$2" ]]; then
        echo  "Nome do arquivo não enviado."
        exit 1
    fi

    if [[ -z "$3" ]]; then
        collection="importacao"
    else
        collection="$3"
    fi

    command="exec -it -w /var/www/mongodumps mongo mongoimport $2 -u root -p root --authenticationDatabase=admin  -d api-nosql -c $collection --jsonArray"

    echo "docker $command"

    docker $command    
}

# funcao que liga as filas de processamento do octo
function queue() {
    echo "#### [$dataAtual] Iniciando filas de processamento do octo ####"
    docker exec -it -w /var/www/html/api-processamento php74 php artisan queue:listen --queue=urgente,alta,media,baixa,muito_baixa --timeout=36000 --memory=512
}

# funcao que liga as filas de criterios
function queueCriteria() {
    echo "#### [$dataAtual] Iniciando filas de criterios do octo ####"
    docker exec -it -w /var/www/html/api-criterios-elegibilidade php74 php artisan queue:listen --timeout=0
}

# funcao que liga as filas de relatorios
function queueReport() {
    echo "#### [$dataAtual] Iniciando filas de relatorios do octo ####"
    docker exec -it -w /var/www/html/api-relatorios php74 php artisan queue:listen --timeout=0
}

# função principal do script
function main() {
    
    if [[ -z "$1" ]]; then
        help
    fi

    for op in "$@"; do
        case "$1" in
            -ac|--accessContainer)
                accessContainer $@
                shift
                exit 0
                ;;        
            -c|--clear)
                clear $@
                shift
                exit 0
                ;;
            -cso|--clearSharedOcto)
                clearSharedOcto
                shift
                exit 0
                ;;
            -csr|--cursor)
                cursor
                shift
                exit 0
                ;;                
            -mi|--mongoimport)
                mongoImport $@
                shift
                exit 0
                ;;                          
            -q|--queue)
                queue
                shift
                exit 0
                ;;
            -qc|--queueCriteria)
                queueCriteria
                shift
                exit 0
                ;;
            -qr|--queueReport)
                queueReport
                shift
                exit 0
                ;;                                 
            -b|--branches)
                branches $@
                shift
                exit 0
                ;;            
                                                       
            -h|--help|*)
                help
                shift
                exit 0
                ;;
        esac
    done
}

main $@