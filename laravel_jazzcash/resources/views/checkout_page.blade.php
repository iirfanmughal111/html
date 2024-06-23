<script>
function submitForm() {
    document.jsform.submit();
}
</script>

<form name="jsform" method="post" action="{{Config::get('constants.jazzcash.TRANSACTION_POST_URL')}}">
    <input type="hidden" name="pp_Version" value="1.1">
    <input type="hidden" name="pp_TxnType" value="MIGS">
    <input type="hidden" name="pp_Language" value="EN">
    <input type="hidden" name="pp_MerchantID" value="">
    <input type="hidden" name="pp_SubMerchantID" value="">
    <input type="hidden" name="pp_Password">
    <input type="hidden" name="pp_BankID" value="TBANK">
    <input type="hidden" name="pp_ProductID" value="RETL">
    <label class="">Ref Number: </label>
    <input type="text" name="pp_TxnRefNo" value="T2017061995819">

    <label class="">Amount: </label>
    <input type="text" name="pp_Amount" value="1000">

    <input type="hidden" name="pp_TxnCurrency" value="PKR">
    <input type="hidden" name="pp_TxnDateTime" value="2017061995819">
    <label class="">Bill Reference: </label>
    <input type="text" name="pp_BillReference" value="billRef">

    <label class="">Description: </label>
    <input type="text" name="pp_Description" value="Description of transaction">

    <input type="hidden" name="pp_TxnExpiryDateTime" value="2017062095819">
    <label class="">Return URL: </label>
    <input type="text" name="pp_ReturnURL">

    <input type="hidden" name="pp_SecureHash" value="">
    <input type="hidden" name="ppmpf_1" value="1">
    <input type="hidden" name="ppmpf_2" value="2">
    <input type="hidden" name="ppmpf_3" value="3">
    <input type="hidden" name="ppmpf_4" value="4">
    <input type="hidden" name="ppmpf_5" value="5">
    <button type="button" onclick="submitForm()">Submit</button>
</form>