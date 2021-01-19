#!/usr/bin/env perl

use DBI;
use Text::Iconv;
use URI::Escape;

# mysql database
my $db_host = "localhost";
my $db_user = "root";
my $db_pass = "";
my $db_base = "etc";
my $dsn = "dbi:mysql:$db_base:$db_host";
my $counter = 0;
my $manual = @ARGV[0];

$converter = Text::Iconv->new("cp1251", "utf-8");
%month2dec = ('января' => 1, 'февраля' => 2, 'марта' => 3, 'апреля' => 4, 'мая' => 5, 'июня' => 6,
	      'июля'	=> 7, 'августа' => 8, 'сентября'=> 9, 'октября' => 10, 'ноября' => 11, 'декабря' => 12);
%tagref = ();

# check ithappens availability
if(system("ping -c 1 ithappens.ru >/dev/null")) { exit; }

# read first it page
open(STR,'curl -s http://ithappens.ru/ |');
my @body = <STR>;
close(STR);

# get stories count
join("\n", @body);
($stories) = m/\<a\shref\=\"\/story\/(\d+)\"\>/ig;

# get stories in db
my $dbh = DBI->connect($dsn, $db_user, $db_pass) || die "Can't connect to DB!";

$dbh->do("set names 'utf8'");
$handle = $dbh->prepare("select MAX(`story_id`) from `ithappens`");
$handle->execute();
$stories_db = $handle->fetchrow();
$handle->finish();

print "Stored stories: $stories_db\n";

my $from = $stories_db+1;

# read stories

if(defined($manual)) {
  $from = $manual;
  $stories = $manual;
}

for($n=$from;$n<=$stories;$n++) {
	open(STR,"curl -s http://ithappens.ru/story/$n |");
	$_ = join("",<STR>);
	close(STR);

	($title) = m/<h1>(.+?)<\/h1>/ig;
	next if ($title eq '');
	$c_title = $dbh->quote($title);

	($date) = m/<div class="date-time">(.+)<\/div>/ig;
	$c_date = $dbh->quote(dateconv($converter->convert($date)));

	($body) = m/<div class="text">(.+?)<\/div>/ig;
	print $body; exit;
	$c_body = $dbh->quote($converter->convert($body));
	next if ($c_body eq '');

	($tags) = m/<div class="tags">(.+?)<\/div>/ig;
	$_ = $converter->convert($tags);
	my @taggy = /<a href="\/tag\/.+?">(.+?)<\/a>/ig;
	$c_tags = $dbh->quote(join(",", @taggy));

	print "$n :: $c_title :: $c_date :: $c_tags\n";


	$query = qq(insert into `ithappens` values ('$n', $c_title, $c_date, $c_body, $c_tags, '0', ''););
	$handle = $dbh->prepare($query);
	$handle->execute();
	++ $counter;
	last if($counter > 250);
}

# update statistic
$handle = $dbh->prepare(qq(insert into `ithappens_sync` (`sync_add`) VALUES ("$counter")));
$handle->execute();

$dbh->disconnect();

sub dateconv {
  shift;
  my ($day,$wmonth,$year,$hour,$min) = m/(\d+)\s(.+)\s(\d+)\,\s(\d+)\:(\d+)/ig;
  my $month = $month2dec{$wmonth};
  return sprintf("%04d-%02d-%02d %02d:%02d",$year,$month,$day,$hour,$min);
}

sub str_replace {
    my $replace_this = shift;
    my $with_this  = shift; 
    my $string   = shift;
    
    my $length = length($string);
    my $target = length($replace_this);
    
    for(my $i=0; $i<$length - $target + 1; $i++) {
	if(substr($string,$i,$target) eq $replace_this) {
	    $string = substr($string,0,$i) . $with_this . substr($string,$i+$target);
	    #return $string; #Comment this if you what a global replace
	}
    }
    return $string;
}

sub url_naturalize {
  my $str_in = shift;
  $str_in = str_replace("\\", "&#92;", $str_in);
#  $str_in = str_replace("'", "&#39;", $str_in);
  return $str_in;
}