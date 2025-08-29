import { SSRBotBase } from './Base/SSRBotBase.js';
import * as cheerio from 'cheerio';
import Table from 'cli-table';

export class FeedReader {
  constructor() {
    this.pages = [
      { type: 'brasil', url: 'https://g1.globo.com/dynamo/brasil/rss2.xml' },
      { type: 'carros', url: 'https://g1.globo.com/dynamo/carros/rss2.xml' },
      { type: 'mundo', url: 'https://g1.globo.com/dynamo/mundo/rss2.xml' },
      {
        type: 'politica',
        url: 'https://g1.globo.com/dynamo/politica/mensalao/rss2.xml',
      },
      {
        type: 'tecnologia',
        url: 'https://g1.globo.com/dynamo/tecnologia/rss2.xml',
      },
      {
        type: 'rj',
        url: 'https://g1.globo.com/dynamo/rio-de-janeiro/rss2.xml',
      },
    ];
  }

  read(type) {
    const page = this.pages.find((p) => p.type == type);

    if (!page) {
      console.log('Feed ainda não adicionado a lista');
    }

    if (page) {
      new SSRBotBase().setUrl(page.url).run((data) => {
        const $ = cheerio.load(data);

        // const noticias = [];

        const table = new Table({
          head: ['data', 'titulo', 'link'],
          colWidths: [20, 100, 40],
        });

        $('item').each((i, el) => {
          const title = $(el).find('title').text();
          const link = $(el).find('guid').text();
          const description = $(el).find('description').text();
          const pubDate = new Date($(el).find('pubDate').text()).toISOString();

          table.push([
            pubDate.replace(/([0-9]+)-([0-9]+)-([0-9]+)/, '$3/$2/$1'),
            title,
            // description,
            link,
          ]);
          // const noticia = {
          //   title,
          //   // description,
          //   link,
          //   pubDate,
          // };

          // noticias.push(noticia);
        });

        console.log(table.toString());

        // noticias.map((noticia, index) =>
        //     table.push(
        //         [index, noticia.title, noticia.pubDate, noticia.link]
        //     )
        // );

        // console.log(table.toString());

        // console.log(noticias);
      });
    }
  }
}
