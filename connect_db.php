<?php # CONNECT TO MySQL DATABASE.

# Connect remote database - details in the .env file
# See 'env.txt' for an example
//$dbc = mysqli_connect ("sql5.freesqldatabase.com", "sql5398544", "SrXM8ettME", "sql5398544")

$dbc = mysqli_connect ( getenv('DB_SERVERNAME'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DBNAME'))

# Otherwise fail gracefully and explain the error. 
OR die ( mysqli_connect_error() ) ;

# Set encoding to match PHP script encoding.
mysqli_set_charset( $dbc, 'utf8' ) ;

