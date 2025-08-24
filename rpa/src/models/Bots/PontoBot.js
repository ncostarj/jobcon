import 'dotenv/config';
import { CSRBotBase } from './Base/CSRBotBase.js';
import { Log } from '../Logs/Log.js';

export class PontoBot {
  constructor() {
    this.url = process.env.CLOCK_URL;
    this.sleep = 15000;
    this.baseBot = new CSRBotBase();
    this.credentials = {
      user: process.env.CLOCK_USER,
      pass: process.env.CLOCK_PASSWORD,
    };
    this.entrada = false;
    this.action = '';
    this.location = 'home_office';
  }

  setAction(action) {
    this.action = action;
    return this;
  }

  setLocation(location) {
    this.location = location;
    return this;
  }

  async run() {
    await this.baseBot
      .setDebug(true)
      .setUrl(this.url)
      .setViewPort(1280, 720)
      .setCredentials(this.credentials)
      .setSleep(this.sleep)
      .run(async (page, browser) => {
        let isLogged = false;

        if (this.baseBot.credentials) {
          Log.info('Logging in');
          await page.type('#username', this.baseBot.credentials.user); //   Type into user box
          await page.type('#password', this.baseBot.credentials.pass); //   Type into password box
          // await page.click('#verifqUsu'); // Click on login button
          await Promise.all([
            page.click('#verifqUsu'), // Clicking the link will indirectly cause a navigation
            page.waitForNavigation(), // The promise resolves after navigation has finished
          ]);

          Log.info('Logged in');
          isLogged = true;
        }

        if (isLogged) {
          let action = this.action;
          let location = this.location;
          // entrada
          await page.evaluate(() => {

            // if (this.action == 'entrada') {
              // Clicar no select
              document.querySelector('#formMarc\\:btnLoc > div > div.loc-icon').click();

              // if(this.location == 'presencial') {
                // Clicar na opção Escritório
                document.querySelector('#formMarc\\:dtLoc_data > tr.ui-widget-content.ui-datatable-even.ui-datatable-selectable > td').click();
              // }

              // if(this.location == 'home_office') {
              //   // Clicar na opção Home Office
              //   document.querySelector("#formMarc\\:dtLoc_data > tr.ui-widget-content.ui-datatable-odd.ui-datatable-selectable > td").click()
              // }

              // clica no gravar marcação de entrada
              document.querySelector('#formMarc\\:j_idt90').click();
            // }

            // // document.querySelector("#formMarc\\:j_idt96").click(); // saida comum

            // if (this.action == 'almoco_saida') {
            //   // botao de saida almoco refeicao descanso
            //   document.querySelector("#formMarc\\:j_idt94").click();
            // }

            // if(this.action == 'almoco_retorno') {
            //    // botao de retorno
            //   document.querySelector('#formMarc\\:j_idt90').click();
            // }

            // if(action == 'saida') {
              // console.log('tste');
            //   // botao de saida normal
              // document.querySelector("#formMarc\\:j_idt91").click();
            // }
          });

          // setTimeout(async () => {
          //   Log.info('Loggin out');
          //   await page.click('.WTButton-Logout');
          //   await browser.close();
          //   Log.info('Logged out');
          // }, this.sleep);
        }
      });
  }
}
