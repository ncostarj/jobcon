import { program } from 'commander';
import { PortalRHBot } from '../../src/models/Bots/PortaRHBot.js';

program
  .description('Comando para automatizar as solicitações no portalrh.')
  .option('-d,--debug')
  .action(async (options) => {
    await new PortalRHBot().run();
  })
  .parseAsync(process.argv);
