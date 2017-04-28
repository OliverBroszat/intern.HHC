from backend.webhandler.soap import SoapFunctions

username = raw_input("Username: ")
password = raw_input("Password: ")

k=SoapFunctions.addAccount(username, password)
print k