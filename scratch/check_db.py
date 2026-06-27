import sys

try:
    import pymysql
    print("pymysql is installed")
except ImportError:
    print("pymysql is NOT installed")

try:
    import mysql.connector
    print("mysql.connector is installed")
except ImportError:
    print("mysql.connector is NOT installed")
