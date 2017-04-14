#! /usr/bin/python
# -*- coding: iso-8859-1 -*-

from backend.database.controller.BaseController import BaseController


class ContactController(object):
    
    DUMMY_CONTACT_DATA = {
        "contact": {
            "prefix": "Herr",
            "first_name": "Vorname",
            "last_name": "Nachname",
            "birth_date": "dd.mm.yyy",
            "comment": "Test-Kommentar"
        },
        "mail": [
            {"description": "Test Mail1", "address": "alex1@alex.de"}
        ],
        "address": [
            {"description": "Privat", "street": "Gilbachstrasse", "number": "7-9", "addr_extra": "", "postal": "40219",
             "city": "Duesseldorf"}
        ],
        "phone": [
            {"description": "Privat1", "number": "0123456789"}
        ],
        "study": [
            {"status": "done", "school": "HHU", "course": "Informatik", "start": "dd.mm.yyyy", "end": "dd.mm.yyyy",
             "focus": "Netzwerksicherheit", "degree": "b_a"}
        ]
    }
    
    def __init__(self, database):
        self.base_controller = BaseController(database)
        self.database = database
    
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    #                        CREATE METHODS
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    
    def create_contact(self, contact, commit=True):
        """
        Creates a new contact in the database given a contact structure. Note: since this contact is
        new, no "id" field in the "contact" data is required, just as no "contact_id" fields are
        required in "mail", "phone", "address" oder "study".

        :param contact: (dict) A contact structure (no IDs required!)
        :param commit: (bool) If true, all changes will be committed at the end of the function call
        :return: (int) The generated contact id for the new contact record
        """
        try:
            contact_id = self.base_controller.insert_row_in_table("contact", contact["contact"], commit=False)
            for mail in contact["mail"]:
                mail["contact_id"] = contact_id
            for address in contact["address"]:
                address["contact_id"] = contact_id
            for phone in contact["phone"]:
                phone["contact_id"] = contact_id
            for study in contact["study"]:
                study["contact_id"] = contact_id

            for mail in contact["mail"]:
                self.base_controller.insert_row_in_table("mail", mail, commit=False)
            for address in contact["address"]:
                self.base_controller.insert_row_in_table("address", address, commit=False)
            for phone in contact["phone"]:
                self.base_controller.insert_row_in_table("phone", phone, commit=False)
            for study in contact["study"]:
                self.base_controller.insert_row_in_table("study", study, commit=False)
            if commit:
                self.database.commit()
            return contact_id
        except BaseException, e:
            self.database.rollback()
            raise e
    
    # TODO: Shouldn't stay for ever
    def create_dummy_contact(self):
        """
        Creates a dummy contact (a contact with fixed data).

        :return: (int) The generated contact id for the new contact record
        """
        self.create_contact(ContactController.DUMMY_CONTACT_DATA)
    
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    #                        READ METHODS
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    
    def select_contact_for_id(self, contact_id):
        """
        Select a contact structure from database for a given ID.

        :param contact_id: (int) The contact id to fetch from the database
        :return: (dict) The matching contact structure or an empty dictionary
        """
        result_contact = self.base_controller.select_rows_by_single_value("contact", "id", contact_id)
        result_mail = self.base_controller.select_rows_by_single_value("mail", "contact_id", contact_id)
        result_phone = self.base_controller.select_rows_by_single_value("phone", "contact_id", contact_id)
        result_address = self.base_controller.select_rows_by_single_value("address", "contact_id", contact_id)
        result_study = self.base_controller.select_rows_by_single_value("study", "contact_id", contact_id)
        if len(result_contact) != 1:
            contact = {}
        else:
            contact = {
                "contact": dict(result_contact[0]),
                "mail": map(dict, result_mail),
                "phone": map(dict, result_phone),
                "address": map(dict, result_address),
                "study": map(dict, result_study)
            }
        return contact
    
    def select_all_contact_ids(self):
        """
        Selects all existing contact IDs from the database.

        :return: ([int]) A list of all existing contact IDs
        """
        all_contacts = self.base_controller.select_columns("contact", ("id",))
        return [row["id"] for row in all_contacts]
    
    def select_all_contacts(self):
        """
        Calls select_contacts_for_ids with select_all_contact_ids() as argument.

        :return: ([dict])  A list of all contact structures
        """
        all_contact_ids = self.select_all_contact_ids()
        all_contacts = []
        for contact_id in all_contact_ids:
            all_contacts.append(self.select_contact_for_id(contact_id))
        return all_contacts
    
    # TODO: ---------------------------------------------------------------*
    # TODO: ---------------------------------------------------------------*
    # TODO:                      ADD FILTER AND SEARCH                     *
    # TODO: ---------------------------------------------------------------*
    # TODO: ---------------------------------------------------------------*
    
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    #                        UPDATE METHODS
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    
    def update_contact(self, contact, commit=True):
        """
        TBD
        :param contact: (dict) A dictionary that contains a valid contact structure
        :return:
        """
        # json ist die gesamte contact struktur
        contact_id = contact["contact"]["id"]
        id_filter = "id={id}".format(id=contact_id)
        contact_id_filter = "contact_id={id}".format(id=contact_id)
        try:
            self.base_controller.update_row("contact", contact["contact"], id_filter, commit=False)
            for mail in contact["mail"]:
                self.base_controller.update_row("mail", mail, contact_id_filter)
            for address in contact["address"]:
                self.base_controller.update_row("address", address, contact_id_filter)
            for phone in contact["phone"]:
                self.base_controller.update_row("phone", phone, contact_id_filter)
            for study in contact["study"]:
                self.base_controller.update_row("study", study, contact_id_filter)
            if commit:
                self.database.commit()
        except BaseException, e:
            self.database.rollback()
            raise e
    
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    #                       DELETE METHODS
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
    
    def delete_contact(self, contact):
        """
        Deletes a given contact structure. NOTE: This method expects a contact structure, not just a
        contact id!

        :param contact: (dict) The contact structure to delete
        :return: (None)
        """
        contact_id = contact["contact"]["id"]
        try:
            self.base_controller.delete_rows_by_single_value("contact", "id", contact_id)
            self.base_controller.delete_rows_by_single_value("mail", "contact_id", contact_id)
            self.base_controller.delete_rows_by_single_value("phone", "contact_id", contact_id)
            self.base_controller.delete_rows_by_single_value("address", "contact_id", contact_id)
            self.base_controller.delete_rows_by_single_value("study", "contact_id", contact_id)
            self.database.commit()
        except BaseException, e:
            self.database.rollback()
            raise e
