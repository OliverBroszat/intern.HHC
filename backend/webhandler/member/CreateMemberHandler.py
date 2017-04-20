import json

import tornado.escape

from backend.webhandler.util import ApiHandler
from backend.database.controller import ContactController
from backend.database.controller import MemberController

class CreateMemberHandler(ApiHandler.ApiHandler):
    def post(self):

        if self.api_token_is_invalid():
            self.write_invalid_api_token_response()
            return
        data = tornado.escape.json_decode(self.get_argument("data"))
        ctr = ContactController.ContactController(self.context.database)
        mtr = MemberController.MemberController(self.context.database)
        if data['newcontact']==True:
            try:
                contact_id = ctr.create_contact(data)
                self.write_success_response(contact_id)
                member_id= mtr.create_member(data,contact_id)
                self.write_success_response(member_id)
            except BaseException, e:
                self.write_error_response(e)
        else:
            print "A:ISUDBSALIDHBSF"
            try:
                member_id= mtr.create_member(data)
                self.write_success_response(member_id)
            except BaseException, e:
                self.write_error_response(e)

    def write_success_response(self, contact_id):
        """
        Write a success JSON response that contains the created contact's id
        :param contact_id: (int) The new contact id
        :return: (none)
        """
        data = {
            "contact_id": contact_id,
            "error": None
        }
        self.write(json.dumps(data))

