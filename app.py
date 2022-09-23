from flask import Flask,render_template,request,jsonify
import asyncio
from bleak import BleakScanner
from datetime import datetime
import pymysql
from flask_mysqldb import MySQL,MySQLdb
import pygame 
from pygame import mixer

app = Flask(__name__)
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'mac_address'
mysql = MySQL(app)

MACID= ""
product_code= ""
mac_add= ""
find=0
result= ""
t=""

async def Scanner():
    global MACID
    global mac_add
    global find

    devices = await BleakScanner.discover()
    for d in devices:
        MACID=d.address.replace(':','')    
        if MACID==mac_add:
            if mac_add!= " ":
                find=1
                break
        else: find=2   
    compare()

def check():
    asyncio.run(Scanner())

def compare():
    global find
    global result

    pygame.mixer.init()

    if find==1:
        result="OK"
        mixer.music.load("Sound_OK.wav")
        mixer.music.play()
    else: 
        result="NG"
        mixer.music.load("Sound_NG.wav")
        mixer.music.play()
    db()

@app.route('/')
def match():
    global product_code
    global mac_add
    global result

    product=request.args.get('pcode')
    mac=request.args.get('mcad')

    product_code = product
    mac_add = mac
    
    check()
    return render_template("match.php", result=result)

def db():
    global product_code 
    global mac_add 
    global result
    
    now = datetime.now()
    dt_string = now.strftime("%Y-%m-%d %H:%M:%S")

    if product_code is not None:
        len_product = len(product_code) #20
        len_mac = len(mac_add) #12
        if len_product==20 and len_mac==12:
            conn = pymysql.connect(host='localhost',user='root',password='',database='mac_address')
            myCursor = conn.cursor()
            myCursor.execute("INSERT INTO compare_result(product_code,mac_address,result,date_time) VALUES(%s,%s,%s,%s);",(product_code,mac_add,result,dt_string))
            conn.commit()
            conn.close()


@app.route('/database')
def displaydata():
    
    cur = mysql.connection.cursor()
    cur.execute("SELECT * FROM compare_result WHERE DATE(date_time) = CURDATE() ORDER BY pid DESC")
    fetchdata = cur.fetchall()
    cur.close()

    return render_template("database.php", db=fetchdata)

@app.route('/report')
def report():
    return render_template("report.php")

@app.route("/ajaxlivesearch",methods=["POST","GET"])
def ajaxlivesearch():
    cur = mysql.connection.cursor(MySQLdb.cursors.DictCursor)
    if request.method == 'POST':
        search_word = request.form['query']
        print(search_word)
        if search_word == '':
            query = "SELECT * FROM compare_result WHERE DATE(date_time) = CURDATE() ORDER BY pid DESC"
            cur.execute(query)
            db = cur.fetchall()
        else:    
            query = "SELECT * from compare_result WHERE product_code LIKE '%{}%' OR mac_address LIKE '%{}%' OR result LIKE '%{}%' OR date_time LIKE '%{}%' ORDER BY pid DESC".format(search_word,search_word,search_word,search_word)
            cur.execute(query)
            numrows = int(cur.rowcount)
            db = cur.fetchall()
            print(numrows)
    return jsonify({'htmlresponse': render_template('filter.html', db=db, rows=numrows)})
    
if __name__=="__main__":
    app.run(debug=True)

