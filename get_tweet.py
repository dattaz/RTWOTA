import json
import requests
import sqlite3
import bs4 as BeautifulSoup
import sys
accounts= [ "NSAGov", "GCHQ" ]
bridge_instance = "https://bridge.suumitsu.eu/"
db = sys.argv[1]
conn = sqlite3.connect(db)
cursor = conn.cursor()
sql = "CREATE TABLE IF NOT EXISTS tweets(id_tweet INTEGER UNIQUE, timestamp INTEGER,  title TEXT, fullname TEXT, avatar TEXT, url TEXT, username TEXT, content TEXT, timelineof TEXT, isanswer INTEGER)"
cursor.execute(sql)
conn.commit()

for account in accounts:
    #Get tweet in json from RSS bridges
    url = bridge_instance + "?action=display&bridge=Twitter&u=" + account + "&format=Json"
    json_content = requests.get(url=url)
    tweets = json_content.json() 
    for tweet in tweets :
        soup_text = BeautifulSoup.BeautifulSoup(tweet["content"], "html.parser")
        #We get html content without <blockquote>
        content = " "
        for x in soup_text.find('blockquote').contents:
            content = content + unicode(x)
        #We check is we have link
        urls =  soup_text.find('blockquote').find_all('a')
        for url in urls:
            #For each link we test is the link is a image link
            if url.text[0:15]  == "pic.twitter.com":
                #If yes we go to this link and get image full url
                image_resp = requests.get(url['href'])
                soup_img = BeautifulSoup.BeautifulSoup(image_resp.text, "html.parser")
                url_img = soup_img.find('meta', attrs={"property":u"og:image"})['content']
                lol = unicode(content)
                #and we remplace a tag (link)  by img tag
                content = lol.replace(unicode(url), unicode('</br><img src="' + url_img + '" style="max-width:80%;"/>"'))
        #We check if a tweets is a answers
        if tweet["title"][0] == "@":
            isanswer = 1
        else:
            isanswer = 0
        sql = "INSERT OR IGNORE INTO tweets(id_tweet, timestamp, title, fullname, avatar, url, username, content, timelineof, isanswer) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        cursor.execute(sql, (tweet["id"],tweet["timestamp"],tweet["title"],tweet["fullname"],tweet["avatar"], tweet["uri"], tweet["username"], unicode(content), account, isanswer))
    conn.commit()
