from suds.client import Client
import hashlib
import ast
import json

def generateKey():
    with open('KasAuthData.json', 'r') as f:
        RawKasAuthData = json.load(f)
    KasAuthData = ast.literal_eval(RawKasAuthData)
    KasPassword = hashlib.sha1()
    KasPassword.update(KasAuthData['password'])

    AuthParams = json.dumps(
        {'KasUser': KasAuthData['login'],
         'KasAuthType': 'sha1',
         'KasPassword': KasPassword.hexdigest(),
         'SessionLifeTime': 600,
         'SessionUpdateLifeTime': 'Y'}
    )
    client = Client('http://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl')
    o = client.service.KasAuth(AuthParams)
    return o
