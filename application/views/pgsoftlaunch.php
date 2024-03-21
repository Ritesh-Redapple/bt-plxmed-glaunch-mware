<?php 
if(!empty($response) && $response['success'])
{
    echo $response['response'];
}else
{
    echo $response['response'];
} 
?>
<script>
    console.log('$response---','<?php echo $response?>');
</script>
