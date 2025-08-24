#!/usr/bin/env node

import { program } from 'commander';
import { Log } from '../src/models/Logs/Log.js';

program
  .version('rpa project 1.0.0', '-v|--version')
  .usage('rpa [options] [commands]')
  .description('RPA CLI')
  .command('ponto', 'Command that runs ponto bot', {
    executableFile: './commands/ponto.js',
  })
  .command('portalrh', 'Command that runs portalrh bot', {
    executableFile: './commands/portalrh.js',
  })
  .command('noticias', 'Command that runs noticias bot', {
    executableFile: './commands/noticias.js',
  })
  .option('-cl,--clear-log', 'Clear application log file')
  .action((options) => {
    if (options.clearLog) {
      Log.clear();
    }
  });

if (process.argv.length < 3) {
  program.help();
}

program.parse(process.argv);
