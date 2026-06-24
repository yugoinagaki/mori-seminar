export default defineCachedEventHandler(async () => {
  try {
    const xml = await $fetch<string>('https://www.nhk.or.jp/rss/news/cat6.xml', {
      parseResponse: (txt) => txt,
    })

    const results: { title: string; link: string; pubDate: string }[] = []
    const re = /<item>([\s\S]*?)<\/item>/g
    let m
    while ((m = re.exec(xml)) !== null) {
      const block = m[1]
      const title   = block.match(/<title><!\[CDATA\[([\s\S]*?)\]\]><\/title>/)?.[1]
                   ?? block.match(/<title>([\s\S]*?)<\/title>/)?.[1]
      const link    = block.match(/<link>([\s\S]*?)<\/link>/)?.[1]
                   ?? block.match(/<guid>([\s\S]*?)<\/guid>/)?.[1]
      const pubDate = block.match(/<pubDate>([\s\S]*?)<\/pubDate>/)?.[1] ?? ''

      if (title && link) results.push({ title: title.trim(), link: link.trim(), pubDate })
    }

    return { results: results.slice(0, 8) }
  } catch {
    return { results: [] }
  }
}, {
  maxAge: 60 * 30, // 30分キャッシュ、その後自動で再取得
})
