    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MCwibmFtZSI6IkNlZXMiLCJlbWFpbCI6ImhhbGxvQGNlZXNyaWprZW4ubmwiLCJwYXNzIjoid3dhY2h0In0.av6LlV1cZIpbnB-Iryiam1lxkP6fiDDfttoB82vSCTE";
    //$data = array("token" => $token, "transaction" => "CREATE TABLE Persons (PersonID int, LastName varchar(255), FirstName varchar(255))", "returnvalue" => "");
    //$data = array("token" => $token, "transaction" => "select * from Persons", "returnvalue" => "fetchAll");
    $data = array("token" => $token, "transaction" => "show tables", "returnvalue" => "fetchAll");    
    //$data = array("token" => $token, "command" => "FILEUPLOAD", "tablename" => "", "conditions" => "", "filename" => "cees.txt", "filedata" => "TXIgU21pdGggaXMgdGhlIG1pc3N1cyBsaXR0bGUgYml0Y2gNCnNoZSdzIG5vdCB0YWxlbnRlZCBlbm91Z2ggdG8ganVzdGlmeSB0aGlzDQoNCml0J3Mgbm90IGxpa2UgeW91J3JlIGEgcHJvcGhldCBvciBzb21ldGhpbmcNCm1vc3Qgb2YgeW91ciBzb25ncyBhcmUgc2ltcGx5IGFib3V0IGZ1Y2tpbmcNCg0KSSdtIGdvbm5hIHN1ZSB5b3UgdGlsbCB5b3Ugc3VpY2lkZQ0Kd291bGQgeW91IGhhdmUgc2xhcHBlZCBUSEUgUm9jayBpbnN0ZWFkIG9mIENocmlzIFJvY2s/DQoNCmhvdyBhbiBpZGlvdCBsaWtlIG1lIG1ha2VzIDMgYWxidW1zIGEgeWVhcg0KDQpNciBTbWl0aCBpcyBqdXN0IHB1c3N5IHdoaXBwZWQNCmFuZCBhbGwgdGhlIHdoaWxlIGdldHRpbmcgamlnZ3kgd2l0aCBpdA0KDQpNciBTbWl0aCBpcyByaWNoIGFzIHNoaXQNCmFuZCBwcm91ZCB0byBiZSBhbGwgcHJpdmlsaWdlZA0KDQpJIGRvbid0IHdlZXAgZm9yIGNlbGVicml0eSBqdW5raWVzDQphbmQgdGhlIG1pbGxpb25haXJlIGlsbGl0ZXJhdGUgaXJvbiBtYW4NCg0KZ2lybCB3aXRoIGd1aXRhciBhbmQgZmxvcHB5IHRpdHMNCmlzIG5vdyBhIGJvb2Jqb2IgYmltYm8gbWFraW5nIGhpdHMNCg0KeW91IGFjdCBhbmQgbG9vayBsaWtlIHBvcm5vIHF1ZWVucw0KDQpXaGF0J3Mgc28gciZyIGFib3V0IERhdmUgR3JvaGwNCkxveWFsdHkgaXMgYSB0aGlja2xlIGJpdGNoIGkgaGVhcg0KDQpCZSBxZnJhaWQgb2YgaWRpb3RzIGlmIHRoZXkgdHJxdmVsIGluIHBhY2tzDQpZb3UgY29uc29sZSB5b3Vyc2VsZiB3aXRoIGNlbGVzdGlhbCBidWxsc2hpdA0KDQpCZWluZyBkZXByZXNzZWQgaXMgdGhlIG5ldyBzZXh5DQpJIHNlZSBvbmxpbmUgeW91IGhhdmUgYSBiZWF1dGlmdWwgbGlmZQ0KDQpUaGlzIG5ldyB5ZWFyIHdpbGwgc2hvdyBob3cgSSBoZWFsDQoNCmxldCdzIHJlcHVycG9zZSBhbGwgb2YgeW91ciBjaHVyY2hlcw0KSSdtIGhlYXIgdG8gYnJlYWtkb3duIHlvdXIgc29saWQgYmVsaWVmcw0KDQpDcmFjayBpcyB3aGFjayBhbmQgdGhlbiB5b3UgZGllDQpmYW1pbHkgZnJpZW5kbHkNCndpdGggYWxjaG9ob2wgYmFubmVkIGF0IGV2ZXJ5IHZlbnVlDQoNCkkgc2F3IHRoZSBmdXR1cmUgb2Ygcm9jayBhbmQgaXQncyBJZGxlcw0KYnV0IHlvdSBjdW50cyBhcmUgc3RpbGwgaHVuZyB1cCBvbiBFbHZpcw0K");
    //$data = array("token" => $token, "transaction" => "FILELIST","returnvalue" => "");
    $postdata = json_encode($data);
    
    $ch = curl_init('https://crm.ceesrijken.nl/v1/'); // Initialise cURL
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    
    $result = curl_exec($ch); // Execute the cURL statement
    curl_close($ch); // Close the cURL connection
    print_r($result);
    