import { createLogger, format, transports } from 'winston';
import 'dotenv/config';
import { fileURLToPath } from 'url';
import { dirname } from 'path';
import path from 'path';
import { truncate } from 'fs';

const { combine, timestamp, label, printf } = format;

const myformat = printf(({ level, label, message, timestamp }) => {
  return `[${timestamp}] ${label}.${level.toUpperCase()}: ${message}`;
});

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const filepath = path.join(
  __dirname + '/../../../storage/logs',
  `/${process.env.APP_NAME}.log`
);

const logger = createLogger({
  level: 'info',
  format: combine(label({ label: process.env.APP_ENV }), timestamp(), myformat),
  transports: [
    new transports.File({
      filename: filepath,
    }),
  ],
});

export class Log {
  static info(message) {
    logger.log('info', message);
  }

  static debug(message) {
    logger.log('info', message);
  }

  static error(message) {
    logger.log('info', message);
  }

  static clear() {
    truncate(filepath, 0, () => {
      console.log('Log cleared successfuly.');
    });
  }
}
