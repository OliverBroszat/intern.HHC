#! /usr/bin/python
# -*- coding: iso-8859-1 -*-

import os
from backend.webhandler.util import PasshashVerifier


class AuthController(object):

    @staticmethod
    def generate_api_token():
        """
        Generates a 128 bit nonce as a string that only contains hex digits based on os.random
        :return: (str) The generated nonce
        """
        return os.urandom(128).encode('hex')

    def check_token(self, username, password):
        """
        Checks the given username and password against the database, returns an api_token if successful
        :param username: (str) The username to authenticate
        :param password:  (str) The transmitted password to check
        :return: (str)
        """
        if self.is_invalid_username(username):
            raise Exception("Invalid User")
        elif self.is_invalid_password(username, password):
            raise Exception("Invalid Password")
        else:
            api_token = self.generate_api_token()
            try:
                self.store_api_token(username, api_token)
                return api_token
            except BaseException, e:
                self.database.rollback()
                raise e

    def __init__(self, database):
        self.database = database

    def is_invalid_username(self, username):
        """
        Returns True if given string is not a valid username
        :param username: (str) The username to test
        :return: (bool)
        """
        query = "SELECT * FROM `users` WHERE username=?;"
        userdata = self.database.get_single_value_by_query(query, (username,))
        if userdata is None:
            return True
        return False

    def is_invalid_password(self, username, password):
        """
        Returns True if the given pair of username and password is not valid
        :param username: (str) The username to verify
        :param password: (str) The password to verify
        :return: (bool)
        """
        salt_query = "SELECT salt FROM `users` WHERE username=?"
        passhash_query = "SELECT passhash FROM `users` WHERE username=?"
        salt = self.database.get_single_value_by_query(salt_query, (username,))
        passhash = self.database.get_single_value_by_query(passhash_query, (username,))
        return not PasshashVerifier.PasshashVerifier.verify(password, salt, passhash)

    def store_api_token(self, username, api_token):
        """
        Stores a generated API token in the database and relates it to one database user
        :param username: (str) The username
        :param api_token: (str) The API token
        :return: (none)
        """
        query = "INSERT INTO api_tokens (username, api_token) VALUES (?,?);"
        args = (username, api_token)
        self.database.cursor.execute(query, args)
        self.database.commit()

    def api_token_is_not_in_database(self, api_token):
        """
        Returns True if a given API token does not exist in the 'api_tokens' table
        :param api_token: (str) The given API token
        :return: (bool)
        """
        query = "SELECT * FROM `api_tokens` WHERE api_token=?;"
        data = self.database.get_single_value_by_query(query, (api_token,))
        if data is None:
            return True
        return False

    def delete_api_token(self, api_token):
        """
        Deletes a given API token from the 'api_tokens' table
        :param api_token: (str) The given API token
        :return: (none)
        """
        query = "DELETE FROM api_tokens WHERE api_token=?;"
        args = (api_token,)
        self.database.cursor.execute(query, args)
        self.database.commit()

    def delete_all_api_tokens(self, api_token):
        """
        Determines the user id for a given token and deletes all API tokens for that id
        :param api_token: (str) A valid API token
        :return: (none)
        """
        username_query = "SELECT username FROM api_tokens WHERE api_token=?;"
        args = (api_token,)
        username = self.database.get_single_value_by_query(username_query, args)
        delete_query = "DELETE FROM api_tokens WHERE username=?;"
        args = (username,)
        self.database.cursor.execute(delete_query, args)
        self.database.commit()