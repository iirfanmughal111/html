<?php
# Step 1: Define the parameters for the Space you want to upload to.
$SPACE="nyc-tutorial-space"; # Find your endpoint in the control panel, under Settings.
$REGION="nyc3"; # Must be "us-east-1" when creating new Spaces. Otherwise, use the region in your endpoint (e.g. nyc3).
$STORAGETYPE="STANDARD"; # Storage type, can be STANDARD, REDUCED_REDUNDANCY, etc.
$KEY="5SGMECSBJ6UPVC2AJ6B4"; # Access key pair. You can create access key pairs using the control panel or API.
$SECRET="$SECRET"; # Secret access key defined through an environment variable.

# Step 2: Define a function that uploads your object via cURL.
function putS3()
{
  $path="."; # The local path to the file you want to upload.
  $file="hello-world.txt"; # The file you want to upload.
  $space_path="/"; # The path within your Space where you want to upload the new file.
  $space="${SPACE}";
  $date=$(date +"%a, %d %b %Y %T %z");
  $acl="x-amz-acl:private"; # Defines Access-control List (ACL) permissions, such as private or public.
  $content_type="text/plain"; # Defines the type of content you are uploading.
  $storage_type="x-amz-storage-class:${STORAGETYPE}";
  $string="PUT\n\n$content_type\n$date\n$acl\n$storage_type\n/$space$space_path$file";
  $signature=$(echo -en "${string}" | openssl sha1 -hmac "${SECRET}" -binary | base64);

  $app = \XF::app();
  $ch = curl_init();
  $data = json_encode(array(
      "ExternalId"=>$user->username.'_'.$user->user_id,
      "Currency"=> $app->options()->fs_escrow_currency,
      "New"=>true,
      "ExpectedAmount"=> 0,
      "CallBackLink"=> ($app->options()->fs_escrow_callback_link.'/bithide_callback.php'),
      "publickey"=> $app->options()->fs_escrow_api,
  ));
  curl_setopt($ch, CURLOPT_URL,$app->options()->fs_escrow_bit_base_url."/address/getaddress");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: $content_type",
  ]);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $server_output = curl_exec($ch);
  $error = curl_error($ch);
  curl_close($ch);
  $response = json_decode($server_output,true);
  
  curl -s -X PUT -T "$path/$file" \ # The cURL command that uploads your file.
  
    -H "Host: $space.${REGION}.digitaloceanspaces.com" \
    -H "Date: $date" \
    -H "Content-Type: $content_type" \
    -H "$storage_type" \
    -H "$acl" \
    -H "Authorization: AWS ${KEY}:$signature" \
    "https://$space.${REGION}.digitaloceanspaces.com$space_path$file"
}

# Step 3: Run the putS3 function.
for file in "$path"/*; do
  putS3 "$path" "${file##*/}" "nyc-tutorial-space/"
done