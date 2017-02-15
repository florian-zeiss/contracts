#!/usr/bin/perl -w
use strict;

# PERL MODULES WE WILL BE USING
use DBI;
use Date::Calc qw(Date_to_Days Add_Delta_Days);
use Email::MIME;

#Datenbankparameter
my $host = "localhost";
my $database = "contracts";
my $tablename = "contracts";
my $user = "username";
my $pw = "password!";

#Datenbankschema
# id		INT			0
# name		VARCHAR		1
# firma		VARCHAR		2
# nummer	VARCHAR		3
# ende		DATE		4
# frist		INT			5
# kosten	FLOAT		6
# email		VARCHAR		7

#DSN = Data Source Name
my $dsn = "dbi:mysql:$database:$host:3306";
 
# now connect and get a database handle  
my $dbh = DBI->connect($dsn, $user, $pass) or die "Can't connect to the DB: $DBI::errstrn";

#prepare the statement
my $sth = $dbh->prepare("select * from $tablename");

#and execute it
$sth->execute; 

#now iterate the result and find the contracts that are about to end
while(@row = $sth->fetchrow_array()) {
	if(calculate($row[4], $row[5]) == 1)
		{			#print "Zeit für einen Wechsel.\n";}
		sendMsg(@row);
		}
}

sub calculate {	#Calculate if a contract is about to end
	my $result = 0;
	my $date = $_[0];
	my $frist = 0 - $_[1] - 7;	#
	my $year = (split(/-/,$date))[0];
	my $month = (split(/-/,$date))[1];
	my $day = (split(/-/,$date))[2];
	($year,$month,$day) = Add_Delta_Days($year,$month,$day, $frist);
	print "$day. $month. $year\n";
	my ($Sekunden, $Minuten, $Stunden, $Monatstag, $Monat, $Jahr, $Wochentag, $Jahrestag, $Sommerzeit) = localtime(time);
	if (Date_to_Days($year,$month,$day) == Date_to_Days($Jahr,$Monat,$Monatstag))
		{$result = 1;}
	return $result;
}

sub sendMsg {
	my $id = $_[0];
	my $name = $_[1];
	my $firma = $_[2];
	my $ende = $_[4];
	my $kosten = $_[6];
	my $email = $_[7];

	my $message = Email::MIME->create(
	  header_str => [
	    From    => 'vertraege@example.com',
	    To      => $email,
	    Subject => "Vertrag mit $firma",
	  ],
	  attributes => {
    	encoding => 'quoted-printable',
	    charset  => 'ISO-8859-1',
	  },
	  body_str => "Der Vertrag mit $firma bezueglich $name laeuft am $ende ab.\nDerzeit fallen Kosten in hoehe von $kosten an.\n\nVielleicht ist es Zeit für einen Wechsel?",);

	# send the message
	use Email::Sender::Simple qw(sendmail);
	sendmail($message);
}


