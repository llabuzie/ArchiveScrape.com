from urllib.request import urlopen as uReq
from internetarchive import *
from bs4 import BeautifulSoup as soup
from tqdm import tqdm
from tkinter import Tk
from tkinter import filedialog
from tkinter.filedialog import askdirectory
from tkinter import *
from tkinter.filedialog import asksaveasfile
import requests

#dataFile = filedialog.askopenfilename(title='Select Data File')
dataFile = filedialog.asksaveasfilename(initialdir = "/",title = "Save csv file",filetypes = (("csv files","*.csv"),("all files","*.*")))
if not '.csv' in dataFile:
	dataFile = dataFile + '.csv'
inputUrl = str(input("Enter the collection identifier : ") )
print(inputUrl)
#could be used, but doesn't have to
pages = 100
urlList = []
identifiers = []
search = search_items('collection:('+inputUrl+')')
for item in search:
	identifiers.append(item['identifier'])

	"""
for x in tqdm(range(1,pages)):
	my_url = str(inputUrl) +'?&sort=-downloads&page='+str(x)

	uClient = uReq(my_url)
	page_html = uClient.read()
	uClient.close()

	page_soup = soup(page_html, "html.parser")

	containers = page_soup.findAll("div", {"class":"item-ttl"})
	if(len(containers)<5):
		break
	for container in tqdm(containers):
		url = container.a["href"]
		newUrl = "https://archive.org" + url
		newnewUrl = newUrl.replace("details", "download")
		#request = requests.get(newnewUrl)
		#if request.status_code == 200:
		"""
for identifier in tqdm(identifiers):
	#identifier = newnewUrl.replace("https://archive.org/download/", "")
	url = "https://archive.org/download/"
	for f in get_files(identifier, glob_pattern='*.mp4'):
		urlList.append(url + identifier +  "/"+ f.name)
		print(url + identifier +  "/"+ f.name)
		

		"""
		client = uReq(newnewUrl)
		download_html = client.read()
		client.close()
		downPage_soup = soup(download_html, "html.parser")
		table = downPage_soup.find("table")
		for rows in table.tbody.findAll("tr"):
			data = rows.td.a
			string = data
			if "href" in str(string) and ".mp4" in str(string):
				downloadUrl = newnewUrl+"/"+string.get("href")
				urlList.append(downloadUrl)
				break
				"""

print(urlList)
filename = dataFile
f = open(filename, "w", encoding="utf-8")
for url in urlList:
	f.write(url+"\n")
f.close()
