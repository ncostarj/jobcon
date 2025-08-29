import { program } from 'commander';
import { PontoBot } from '../../src/models/Bots/PontoBot.js';

program
  .description('Comando para automatizar o registro do ponto.')
  .option('-d,--debug')
  .action(async (options) => {
    await new PontoBot()
      .setAction('saida')
      .setLocation('presencial')
      .run();
  })
  .parseAsync(process.argv);
