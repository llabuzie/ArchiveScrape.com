from urllib.request import urlopen as uReq
from internetarchive import *
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

for identifier in tqdm(identifiers):
	url = "https://archive.org/download/"
	for f in get_files(identifier, glob_pattern='*.mp4'):
		urlList.append(url + identifier +  "/"+ f.name)
		print(url + identifier +  "/"+ f.name)

print(urlList)
filename = dataFile
f = open(filename, "w", encoding="utf-8")
for url in urlList:
	f.write(url+"\n")
f.close()
