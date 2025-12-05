import fetch from 'node-fetch';

export class SSRBotBase {
  constructor() {}

  setUrl(url) {
    this.url = url;
    return this;
  }

  run(callback) {
    return fetch(this.url)
      .then((response) => response.text())
      .then((data) => {
        callback(data);
      });
  }
}
