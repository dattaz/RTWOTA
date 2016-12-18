import sqlite3
import sys
import time
import bs4 as BeautifulSoup
import datetime
if len(sys.argv) < 2:
	print "Usage : python TL.py [db path] [1 si affichage des reponses, 0 sinon]"
	sys.exit()
db = sys.argv[1]
if len(sys.argv) == 3:
	answer = int(sys.argv[2])
else:
	answer = 0
conn = sqlite3.connect(db)
cursor = conn.cursor()

last = 0
while True:
	cursor.execute("""SELECT id_tweet, timestamp, title, fullname, avatar, url, username, content, timelineof, isanswer FROM tweets ORDER BY timestamp """)
	rows = cursor.fetchall()
	for row in rows[-10:]:
		if row[1] > last:
			last=row[1]
			if (row[9] == 1 and answer == 1) or row[9] == 0:
				text = BeautifulSoup.BeautifulSoup(row[7], "html.parser")
				print row[3] + "(" + row[6] + "): " + text.text + " -- " +  datetime.datetime.fromtimestamp(row[1]).strftime('%Y-%m-%d %H:%M:%S')

	time.sleep(60)
