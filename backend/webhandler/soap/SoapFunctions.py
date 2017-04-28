from suds.client import Client
import json
import ast
import hashlib
import backend.webhandler.soap.GenerateKasAuthKey as gk

def performAction(action, UserParams):
    try:
        with open('KasAuthData.json', 'r') as f:
            RawKasAuthData=json.load(f)
        KasAuthData=ast.literal_eval(RawKasAuthData)
        KasPassword=hashlib.sha1()
        KasPassword.update(KasAuthData['password'])

        Token= gk.generateKey()


        RequestParams=json.dumps(
        {'KasUser': KasAuthData['login'],
        'KasAuthType': 'session',
        'KasAuthData':Token,
        'KasRequestType': action,
        'KasRequestParams': UserParams}
        )

        client = Client('http://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl')
        result= client.service.KasApi(RequestParams)
        return result
    except BaseException, e:
       raise e

def addAccount(name,password):
    UserParams = {'mail_password': password,
                  'local_part': name,
                  'domain_part': 'hhc-duesseldorf.de',
                  'responder': '1230764400|1259622000',
                  'responder_text': 'Ich bin zurzeit nicht erreichbar',
                  'copy_adress': 'kopie@hhc-duesseldorf.de',
                  'mail_sender_alias': 'test@web.de',
                  }
    performAction("add_account",UserParams)

def deleteAccount(name, password):
    UserParams={'mail_login': name
    }
    performAction("delete_account",UserParams)

def editAccount(email,password):
    UserParams = {'mail_login': email,
                  'mail_new_password': password,
                  'responder': '1230764400|1259622000',
                  'responder_text': 'Ich bin zurzeit nicht erreichbar',
                  'copy_adress': 'kopie@hhc-duesseldorf.de',
                  'is_active':'Y'
                  'mail_sender_alias': 'kopie@web.de'
                  }
    performAction('update_mailaccount',UserParams)