import { program } from 'commander';
import { FeedReader } from '../../src/models/Bots/FeedReader.js';

program
  .description('Comando para ler as ultimas noticias do dia.')
  .option('-t,--type <type>')
  .action(async (options) => {
    const type = options.type ?? 'brasil';
    new FeedReader().read(type);
  })
  .parse(process.argv);
