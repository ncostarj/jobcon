import puppeteer from 'puppeteer';
import { Log } from '../../Logs/Log.js';

export class CSRBotBase {
  constructor() {}

  setDebug(debug) {
    this.debug = debug;
    return this;
  }

  setUrl(url) {
    this.url = url;
    return this;
  }

  setViewPort(vpWidth, vpHeight) {
    this.vpWidth = vpWidth;
    this.vpHeight = vpHeight;
    return this;
  }

  setCredentials(credentials) {
    this.credentials = credentials;
    return this;
  }

  setOptions(option) {
    this.option = option;
    return this;
  }

  setSleep(miliseconds) {
    this.sleep = miliseconds;
    return this;
  }

  setAction(action) {
  }

  async run(callback) {
    // Log.info("Begin automation process");
    Log.info('Begin automation process');

    const options = {
      args: [`--window-size=${this.vpWidth},${this.vpHeight}`],
    };

    if (this.debug) {
      options.headless = false;
    }

    // Launch the browser and open a new blank page
    const browser = await puppeteer.launch(options);
    const page = await browser.newPage();

    Log.info(`Accessing the website ${this.url}`);

    await page.goto(this.url); // Navigate the page to a URL
    await page.setViewport({ width: this.vpWidth, height: this.vpHeight }); // Set screen size

    Log.info(`Executing actions inside a page`);

    callback(page, browser);
  }
}
