import 'dotenv/config';
import { CSRBotBase } from './Base/CSRBotBase.js';
import { Log } from '../Logs/Log.js';

export class PortalRHBot {
  constructor() {
    this.url = process.env.PORTALRH_URL;
    this.sleep = 8000;
    this.baseBot = new CSRBotBase();
    this.credentials = {
      user: process.env.PORTALRH_USER,
      pass: process.env.PORTALRH_PASS,
    };
  }

  async run() {
    await this.baseBot
      .setDebug(true)
      .setUrl(this.url)
      .setViewPort(1920, 1024)
      .setCredentials(this.credentials)
      .setSleep(this.sleep)
      .run(async (page, browser) => {
        let isLogged = false;

        if (this.baseBot.credentials) {
          Log.info('Logging in');
          await page.type('#usuario', this.baseBot.credentials.user); //   Type into user box
          await page.type('#senha', this.baseBot.credentials.pass); //   Type into password box
          await page.click('#btnLogin'); // Click on login button
          Log.info('Logged in');
          isLogged = true;
        }

        if (isLogged) {
          setTimeout(async () => {
            Log.info('Loggin out');
            await page.click('#colaboradorNomeLink');
            await page.click(
              '#menuSuperior > li.open > ul > li:nth-child(4) > a'
            );
            await browser.close();
            Log.info('Logged out');
          }, this.sleep);
        }
      });
  }
}
