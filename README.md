# aa



from hashlib import md5
from Crypto.Cipher import AES

token = '305c300d06092a864886f70d0101010500034b003048024100d812a482263f7f6fe89756af3e50cd3ee12b66c5977f996994df948e05a69aebf422ca1bb8567231531dd574ead8a959ac6f8067718effcb01591e5649e99fb70203010001'
username = '18206774149'
password = 'aa8888'

md5pass = md5(password.encode('utf-8')).hexdigest()

encryption_suite = AES.new(token, AES.MODE_CBC, '')
cipher_text = encryption_suite.encrypt(username + ',' + md5pass)

print(cipher_text)




----------------------------------------------------------------------------
pip install pycryptodome
