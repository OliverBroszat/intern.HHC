from backend.database.controller.BaseController import BaseController
from backend.database.controller.ContactController import ContactController

class MemberController(object): #warum object?

    def __init__(self, database):
        self.base_controller = BaseController(database)
        self.contact_controller=ContactController(database)
        self.database = database
    def create_member(self,data,contact_id=None, commit=True):
        if contact_id is not None:
            print "hue"
            data['member']['contact_id']=contact_id
            try:
                member_id=self.base_controller.insert_row_in_table("member", data['member'], commit=False)
                if commit:
                    self.database.commit()
                return member_id
            except BaseException, e:
                self.database.rollback()
                raise e
        else:
            try:
                print "huehue"
                member_id =self.base_controller.insert_row_in_table("member", data['member'], commit=False)
                if commit:
                    self.database.commit()
                return member_id
            except BaseException, e:
                self.database.rollback()
                raise e

    def delete_member(self,data):

        if data['keepcontact']:
            self.base_controller.delete_rows_by_single_value('member','contact_id', data['contact_id'])
        else:
            self.base_controller.delete_rows_by_single_value('member','contact_id', data['contact_id'])
            contact={'contact':{'id':data['contact_id']}}
            self.contact_controller.delete_contact(contact)