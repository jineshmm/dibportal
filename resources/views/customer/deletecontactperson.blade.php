

                        {{ Form::open(array('route' => array('deletecontactperson', $customerId,$contactId), 'name' => 'form_contact_person_delete','id'=>'form_contact_person_delete','files'=>'true' )) }}
                        


    @csrf 
                     
                            <div class="dialogform" id="fieldgroup_addperson">
                                Do you really want to delete this data ?
                            </div>
  {{ Form::close() }}  
  
  