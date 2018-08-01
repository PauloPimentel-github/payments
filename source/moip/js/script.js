$(document).ready(function() {
  $("#encrypt").click(function() {
    var cc = new Moip.CreditCard({
      number  : $("#number").val(),
      cvc     : $("#cvc").val(),
      expMonth: $("#month").val(),
      expYear : $("#year").val(),
      pubKey  : $("#public_key").val()
    });
    console.log(cc);
    if( cc.isValid()){
      $("#encrypted_value").val(cc.hash());
    }
    else{
      $("#encrypted_value").val('');
      alert('Invalid credit card. Verify parameters: number, cvc, expiration Month, expiration Year');
    }
  });
});
