#! /usr/bin/python
# -*- coding: iso-8859-1 -*-

import json

import tornado.escape

from backend.webhandler.util import ApiHandler
from backend.database.controller import MemberController


class DeleteMemberHandler(ApiHandler.ApiHandler):
    def post(self):
        """
        Post handler for DeletecontactHandler
        :return: (none)
        """
        if self.api_token_is_invalid():
            self.write_invalid_api_token_response()
            return
        data = tornado.escape.json_decode(self.get_argument("data"))
        mtr = MemberController.MemberController(self.context.database)
        try:
            mtr.delete_member(data)
            self.write_success_response()
        except BaseException, e:
            self.write_error_response(e)

    def write_success_response(self):
        """
        Write a JSON answer containing no error messages
        :return: (none)
        """
        data = {
            "error": None
        }
        self.write(json.dumps(data))
