#! /usr/bin/python
# -*- coding: iso-8859-1 -*-

import json

import tornado.web
import tornado.escape

from backend.webhandler.util import ApiHandler
from backend.database.controller import ContactController


class CreateContactHandler(ApiHandler.ApiHandler):

    def post(self):
        if self.api_token_is_invalid():
            self.write_invalid_api_token_response()
            return
        data = tornado.escape.json_decode(self.get_argument("data"))
        ctr = ContactController.ContactController(self.context.database)
        try:
            contact_id = ctr.create_contact(data)
            self.write_success_response(contact_id)
        except BaseException, e:
            self.write_error_response(e)
    
    def write_success_response(self, contact_id):
        data = {
            "contact_id": contact_id,
            "error": None
        }
        self.write(json.dumps(data))
