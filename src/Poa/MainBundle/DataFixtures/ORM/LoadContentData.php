<?php

// src/Poa/MainBundle/DataFixtures/ORM/LoadContentData.php

namespace Poa\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Poa\MainBundle\Entity\Content;

class LoadContentData extends AbstractFixture implements OrderedFixtureInterface
{
	protected $menu_order;

	public function __construct()
	{
		$this->menu_order = 0;
	}

	public function build($route,$sequence,$text,$active=true,$expires=null)
	{
		$contentData = new Content();
		$contentData->setMenuItem($this->getReference($route)->getId());
		$contentData->setSequence($sequence);
		$contentData->setText($text);
		$contentData->setActive($active);
		$contentData->setExpires($expires);

		return $contentData;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$content = $this->build(
			'_about_us',
			1,
			'The Ballard Woods subdivision is centrally located between Fuquay Varina, NC and Lillington, NC the "Heart" of Harnett County. We are the ideal location offering a sense of relaxation in a hectic world, where neighbors can meet and children can grow up, yet uniquely blending the conveniences of nearby shopping, excellent schools and recreation.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_about_us',
			2,
			'Jordan Lake, Harris Lake, and Raven Rock Park nearby, sports and other types of outdoor recreational activities are very popular. The Parks, Recreation and Cultural Resources Department for both Harnett and Wake Counties manage more than 40 parks and sports fields, plus a gym and community center.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_about_us',
			3,
			'Lillington is the County Seat and is located in the central portion of Harnett County. Lillington is approximately 4 square miles and is located between Raleigh, the State&apos;s Capital, and Fayetteville, home of Fort Bragg and Pope AAB.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_about_us',
			4,
			'Fuquay-Varina, NC is an up-and-coming suburban town located just 30 minutes outside the state capital of Raleigh, NC. Centrally located near Raleigh and the Research Triangle Park, Fuquay-Varina is appealing to future homeowners who are looking for a convenient and family-oriented community.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_about_us',
			5,
			'District Schools for Ballard Woods are Harnett Lafayette Elementary, Harnett Central Middle School, and Harnett Central High School. Cape Fear Christian Academy is also within a 15 minutes of our community.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_about_us',
			6,
			'College students have several options with North Carolina State University at Raleigh, Wake Technical Community College, University of Chapel Hill - North Carolina, and Meredith College all within 30 miles of Fuquay-Varina and Campbell University only a 10 minutes drive from Ballard Woods.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_amenities',
			1,
			'[h1]Playground[/h1]

The playground is located behind the clubhouse and pool. The playground has a variety swings, slides, climbing equipment for kids up to 12 years old.

[br/]

[center][img height="291" width="501"]/test/web/bundles/poamain/images/playground.jpg[/img][/center]
',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_amenities',
			2,
			'[h1]Club House[/h1]

The clubhouse is located at 215 Ruth Circle, adjacent to the pool. It is used for community meetings and functions and social events.

[br/]

The clubhouse can be rented by Ballard Woods residents.

[br/]

[center][img height="291" width="501"]/test/web/bundles/poamain/images/clubhouse.jpg[/img][/center]
',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_amenities',
			3,
			'[h1]Swimming Pool[/h1]

Ballard Woods residents enjoy their neighborhood pool from May through mid-September.

[br/][br/]

It is professionally maintained by a local pool management company.

[br/][br/]

Residents use their keys to access the pool.

[br/]

[center][img height="291" width="501"]/test/web/bundles/poamain/images/pool_deck1.jpg[/img][/center]

[br/]

[center][img height="291" width="501"]/test/web/bundles/poamain/images/pool_deck2.jpg[/img][/center]

[br/]

[center][img height="291" width="501"]/test/web/bundles/poamain/images/pool_deck3.jpg[/img][/center]

[br/]

[center][img height="291" width="501"]/test/web/bundles/poamain/images/pool_deck4.jpg[/img][/center]
',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_board',
			1,
			'Your Ballard Woods Home Owners&apos; Association Board of Directors consists of officers elected by members of the community. They oversee the administration of service contracts, community amenities and neighborhood projects that benefit the community. They set the budget and the annual assessment.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_board',
			2,
			'Board Members:[br/] Kevin Horsman - President[br/] Marshall Putnam - Vice President[br/] Cindy MacDonald - Vice President[br/] Garnell Heggie - Assistant Vice President[br/] Marc Nemeth - Treasurer[br/] Bud Minton - Assistant Treasurer[br/] Debbie Skubon - Secretary[br/] Robin Harrison - Assistant Secretary[br/][br/]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_board',
			3,
			'The Board has set up several committees to help maintain and enhance the standards, amenities and lifestyle of the neighborhood.[br/]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_board',
			4,
			'Architectural Requests - ?[br/] Grounds - ?[br/] Recreation - ?[br/] Social - ?[br/] Newsletter - ?[br/] Neighborhood Watch - ?[br/] Webmaster - Mark Peters[br/][br/]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_board',
			5,
			'To contact a Board member or committee chair, use the form found on the <a href="/controller.php?view=contact">Contact Us</a> page.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_management',
			1,
			'Ballard Woods is managed by Associa/HRW. They maintain records, help expedite contracts, collect assessments and perform various vital administrative roles for our community.





Email HRW by selecting HRW(Management) on the drop-down list on the [url=/controller.php?view=contact]Contact Us[/url] form.





As part of our service, we are very pleased to let you know about a number of convenient options that we offer, which are intended to enhance and streamline your interactions with the association.





For review and payment of your assessment account, note the following:





[list]

[li]Homeowners have real-time online access to their account history through the Associa Access program.  Just visit [url=http://www.hrw.net target=_blank]www.hrw.net[/url] and click "Homeowners", then "Owner Login" to view your account.  You will be able to see your account history (from February 2012 forward) and view transactions at your convenience, 24 hours a day.  Note that the account number from your billing statement is required for first-time registration.[/li]



[li]Online credit card payment capability with full security and real-time posting to homeowner accounts is available.  We are very excited to have this convenient payment method available for you.  Be aware that the credit card company does charge a convenience fee to the homeowner for use of their system, but it is great to have the option available in case of emergency.[/li]



[li]Assessment payment by automatic draft is a convenient option that is available to homeowners in all HRW communities.  You can access forms at [url=http://www.hrw.net target=_blank]www.hrw.net[/url], send in your information, and we will set up your bank account for automatic withdrawal when your next assessment is due.  The one-time enrollment can be stopped at any time, and it is the easiest way to avoid late fees. Your board encourages this payment method, as it is cost effective and convenient.[/li]

[/list]



[url="http://hrw.net/" target="_blank"]HRW, Inc.[/url]



4700 Homewood Court, Suite 380

Raleigh, NC 27609

Telephone:919-787-9000

Fax 919-783-9534





Property manager: Sabrina Summerall

Direct line: 919-786-8012

e-mail: ssummerall@hrw.net',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_management',
			2,
			'[hr/]

[b]Associa CEO Direct: A message from Cathy Wade, President and Chief Executive Officer of HRW, Inc.[/b]



Good Afternoon!



To further connect with our communities and homeowners, Associa is proud to announce the rollout of the [i]"Associa Lifestyle"[/i] [url="http://pinterest.com/associa/" target="_blank"]Pinterest[/url] page.



Here, you will have access to the many lifestyle boards, including Spring Sustainability, Savvy Home Tips and Living Practical, all from Associa Lifestyle programs, including [url="http://www.associasupportskids.org/" target="_blank"]Associa Supports Kids[/url], [url="http://associagreen.com/" target="_blank"]Associa Green[/url], [url="http://associationtimes.associaliving.com/" target="_blank"]Association Times[/url] and [url="http://associaliving.com/" target="_blank"]Associa Living[/url] and a few more...



With the launch of its Pinterest page, Associa has created the [url="http://www.associaonline.com/AssociaExternalDocs/Associa_SocialKit_HIRES.pdf" target="_blank"]Associa Social Kit[/url] just in time for summer event planning.  Available to community managers, board members and homeowners, the Associa Social Kit includes all the tools you need to know how to plan the ideal community event.



[b]Are your communities in need of a little extra cash for their Social Events this summer?[/b]   To make hosting an event in your community easier, Associa is introducing the [b]Associa Summer Smash Giveaway[/b]. Entering is easy. Associa community residents "Like" the Associa [url="http://www.facebook.com/associa" target="_blank"]Facebook[/url] page and fill out the form under the "Associa Social Kit" tab from May 24 - June 8. The winner, determined through a random drawing, will receive $2,500 for a community event included in the Social Kit (Note: the event must occur before December 1, 2012). We\'ll notify the winner the first week of June.



		In the kit, you will find a [b]pre-event checklist[/b] and [b]communications tips and tools[/b] to get you started with planning your event.   This summer, consider hosting one of the following events in a community you serve:   Associa Supports Kids (ASK) Associa Green Carnival Day 4th of July Pool Party National Night Out   The Associa Social Kit is available to all communities on Pinterest, Associa Living and the corporate and branch websites (under the "Resources" tab).



Best Regards,

Cathy Wade, CMCA&#174;, AMS&#174;, PCAM&#174; President & CEO [size="10"]

		[i]HRW inc, AAMC&#174;  An Associa&#174; Company Associa&#174;...The leader in association management[br/]

		4700 Homewood Court, Suite 380, Raleigh NC 27609[br/]

(919) 787-9000 ph[br/]

(919) 783-9534 fax[/i][/size][br/]

[url target="_blank"]www.associaadvantage.com[/url] - [color="#943634"][size="14"]Providing exceptional discounts on household goods and services to millions of homeowners nationwide[/size][/color][br/]

[url target="_blank"]http://associationtimes.com/[/url] - [color="#943634"][size="14"]an invaluable resource for Community Associations[/size][/color][br/]

[url target="_blank"]http://www.hrw.net/[/url] - [color="#943634"][size="14"]forms, information, account access and more[/size][/color]',
			true,
			\DateTime::createFromFormat('Y-m-d H:i:s','2012-06-09 00:00:00')
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			1,
			'[size="21"][b]Committee Overview[/b][/size]



Our community thrives because of its many volunteers. Many Ballard Woods residents serve on committees that make our neighborhood a special place to live.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			2,
			'[size="14"][b]Recreation[/b][/size]



The committee works on projects dealing with the pool, club house, and play areas. The Recreation Committee has many ongoing projects and is active throughout the year. If you would like to learn more about the committee or to volunteer, come to the next meeting or [url=/controller.php?view=contact]contact[/url] the chair.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			3,
			'[size="14"][b]Social[/b][/size]



The Ballard Woods Social Committee plans, organizes and executes a variety of events throughout the year for our community. This committee also oversees the pool with direction of the HOA President and Officers. We are ALWAYS looking for new people and new ideas to help make all of our events a success and the pool a safe and enjoyable location to relax and entertain. Meetings are very informal and on an as needed basis. Joining our committee is a great way to meet people too! If you would like to learn more about the committee or to volunteer, [url=/controller.php?view=contact]contact[/url] the chair.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			4,
			'[size="14"][b]Grounds[/b][/size]



The Grounds Committee provides oversight for the maintenance and continuous improvement of common areas within the neighborhood. The primary areas we focus on are the entrances to the community and property around the pool area, in which we are responsible for things like lighting, fences, irrigation systems, and landscaping-items like trees, shrubs, flowers, and grass-areas. The committee\'s main role is to provide direction to the grounds maintenance contractor for routine items and special landscape-related projects.  The Grounds Committee also coordinates decorating of the entrances for the holidays to give our community a festive look.  If you would like to learn more about the committee or to volunteer [url=/controller.php?view=contact]contact[/url] the chair.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			5,
			'[size="14"][b]Architectural[/b][/size]



Planning a landscaping, decking or similar construction project this spring? Don\'t forget to submit an architectural approval request prior to beginning the project. Please refer to the homeowner\'s covenants regarding architectural approval process. The committee members work to handle each request in a timely manner. If you would like to learn more about the committee or to volunteer, contact the chair.



[url=/documents/Ballard Woods ARC Request Form 2012-02-22.pdf]Architectural Request Form[/url]

[url=/documents/Ballard Woods ARC guidelines 2012-02-22.pdf]Architectural Committee Guidelines[/url]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			6,
			'[size="14"][b]Neighborhood Watch[/b][/size]



We are currently seeking a few residents to take the lead in this important function. This committee is responsible for monitoring and tracking all incidents in the community including possible vandalism, break-ins, speeding, loitering, trespassing, etc. These persons will assistance from the HOA President and Officers will be the a contact for the Harnett County Sheriffs office and point person for homeowners to report instances of possible concern. ALWAYS report and suspicious activity in the neighborhood to the Harnett County Sheriffs office FIRST by calling either the non-emergency line at 919-893-911 or dialing 911 for an emergency or immediate response. This committee is always in need of members.  If you would like to learn more about the committee or to volunteer [url=/controller.php?view=contact]contact[/url] the chair.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_committees',
			7,
			'[size="14"][b]Website[/b][/size]



Oversight and development of the community webpage.  If you would like to learn more about the committee or to volunteer [url=/controller.php?view=contact]contact[/url] the chair.  Use the form on the [url=/controller.php?view=contact]Contact Us[/url] page to volunteer for a committee or to leave a comment.',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_pool',
			1,
			'[center][img height="291" width="501"]/test/web/bundles/poamain/images/pool_deck1.jpg[/img][/center]



[size="14"][b]Ballard Woods Homeowners Association Pool and Associated Property Rules[/b][/size]



One Pool key is issued per assessed lot free of charge. Additional or replacement Pool Keys must be requested by e-mailing the Pool Committee in the Contact Us page. A charge of $10, non refundable will be assessed for additional or replacement keys. Homeowners must have a key to enter pool and must not swim without an authorized entry key.



Pool Season: Begins May 26th (Saturday of Memorial Day Weekend) and ends early to mid September 2012



[size="14"][b]HOURS:[/b][/size]



7 days a week: Pool will be open at 9AM and closes at 9PM, with no lifeguard and its SWIM AT YOUR OWN RISK.



Cleaning:  <!-- Cleaning times are targeted for Wednesday 8:00 AM to 10:00 AM and Sunday 8:00 AM to 10:00 AM -->



Late Hours: Everyone must vacate the pool area when the pool hours end.



[size="20"][b]RULES[/b][/size]



[size="14"][b]GENERAL FACILITY RULES[/b][/size]



In case of emergency dial 9-1-1 on the phone located inside the clubhouse.



[list]

[li]No running, pushing or horseplay is permitted in the pool area.[/li]

[li]No skates, skateboards, skate shoes, scooters, or bicycles are permitted in the recreation area.[/li]

[li]No animals are permitted in the gated pool area. (Exception for specially trained service animals)[/li]

[li]Loitering or playing in the bathrooms, showers or parking area will not be permitted.[/li]

[li]No glass bottles or objects in the pool area at any time.[/li]

[li]No weapons of any kind are permitted in any HOA common areas.[/li]

[/list]



[list]

[li]All personal belongings and litter should be removed from the premises or properly discarded upon exiting the area.[/li]

[li]All members and their guests using the BWHOA facilities must conduct themselves in a courteous manner to ensure the safety of all.[/li]

[li]No abusive language, profanity or disruptive behavior will be tolerated.[/li]

[li]The BWHOA is not responsible for the loss, theft, or damage to personal property of members or their guests.[/li]

[/list]



[size="14"][b]BALLARD WOODS POOL RULES[/b][/size]



[list]

[li]NO RUNNING[/li]

[li]NO BOISTEROUS PLAY[/li]

[li]NO DIVING ALLOWED IN POOL[/li]

[li]CHILDEN UNDER 14 SHOULD NOT USE THE SWIMMING POOL WITHOUT ADULT SUPERVISION[/li]

[li]ADULTS SHOULD NOT SWIM ALONE[/li]

[li]NO PERSON UNDER THE INFLUENCE OF ALCOHOL OR DRUGS SHOULD USE THE POOL[/li]

[li]NO PERSON WITH SKIN, EAR, OR NASAL INFECTIONS ALLOWED IN THE POOL[/li]

[li]NO PERSON WITH COMMUNICABLE DISEASE ALLOWED IN THE POOL[/li]

[li]NO ANIMALS OR PETS ALLOWED IN THE POOL OR ON THE DECK[/li]

[li]NO GLASS ALLOWED IN THE POOL OR ON THE DECK[/li]

[li]ALL PERSONS USING THE POOL DO SO AT THEIR OWN RISK.  OWNERS AND MANAGEMENT ARE NOT RESPONSIBLE FOR ACCIDENTS, INJURIES OR DEATH.[/li]

[li]POOL IS FOR PRIVATE USE. MEMBERS AND GUEST ONLY[/li]

[li]MANAGEMENT RESERVES THE RIGHT TO DENY USE OF THE POOL TO ANYONE AT ANY TIME[/li]

[li]THIS POOL IS OPEN FROM [color="red"][b]9[/b][/color] AM TO [color="red"][b]9[/b][/color] PM[/li]

[li]EMERGENCY TELEPHONE IS LOCATED ON THE BACL WALL UNDER THE COVERED AREA OF THE DECK[/li]

[/list]



[size="14"][b]Notes from the Management[/b][/size]



[list]

[li]CHILDREN WHO ARE NOT TOILET TRAINED MUST USE SWIN DIAPERS [u]AND A PLASTIC PANTS/UNDERWEAR DIAPER COVER[/u].[/li]

[li][u]NO SMOKING INSIDE FENCED AREA.[/u][/li]

[li]CLEAN UP AFTER YOURSELF, CHILDREN AND GUESTS. DO NOT LEAVE ANYTHING BEHIND. [u]MANAGEMENT IS NOT RESPONSIBLE FOR MISPLACED OR LOST ITEMS[/u].[/li]

[/list]



[size="14"][b]BALLARD WOODS POOL CONTACT INFORMATION[/b][/size]



[div][div][u]POOL ADDRESS:[/u] [/div] [div] 215 RUTH CIRCLE [/div][/div]

[div][div][u]POOL MANAGER:[/u] [/div] [div] SMD POOL & SPA SERVICE STEFAN DABROWSKI 919-342-5177 OR 252-599-2922 [/div][/div]

[div][div][u]MANAGEMENT COMPANY:[/u] [/div] [div] ASSOCIA HRW 919-787-9000 [/div][/div]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			1,
			'[size="15"][b]Useful Links[/b][/size]

[size="14"]These links can make your life much simpler. There are many services available online that used to involve standing in long lines or waiting on "hold" for hours. You still can\'t take your behind the wheel driver\'s test or renew your expired license online, but you can replace a lost driver\'s license via the web.[/size]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			2,
			'[br/]

[size="14"][b]North Carolina:[/b][/size]

[list]

[li][url="http://www.visitnc.com" target="_blank"]Visit NC[/url][/li]

[li][url="http://www.lillingtonnc.com" target="_blank"]Town of Lillington, NC[/url][/li]

[li][url="http://www.fuquay-varina.org" target="_blank"]Town of Fuquay Varina, NC[/url][br/]Ballard Woods is located in between Lillington and Fuquay Varina, North Carolina.[/li]

[li][url="http://www.harnett.org" target="_blank"]Harnett County Government[/url][br/]Ballard Woods is located in Harnett County and residents can find information about voter registration, deeds, human services and taxes among other things at the county\'s website.[/li]

[li][url="http://www2.ncocc.org/ncocc/default.htm" target="_blank"]North Carolina One Call Center[/url][br/]State law requires homeowners to have utility lines marked before digging on their property. Call 1-800-632-4949 for North Carolina\'s one call center (they will contact all the utility companies for you) or 811 for the national center.[/li]

[/list]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			3,
			'[br/]

[size="14"][b]Education:[/b][/size]

[list]

[li][url="http://www.harnett.k12.nc.us" target="_blank"]Harnett County Schools[/url][br/]Ballard Woods is part of the Harnett County Schools System which offers a variety of specialized programs for its students. Base schools for Ballard Woods residents are either Lafayette Elementary; Harnett Center Middle School, and Harnett Centeral High School.[/li]

[li][url="http://www.50states.com/cc/ncarolin.htm" target="_blank"]North Carolina Community Colleges[/url][/li]

[li][url="http://www.50states.com/college/ncarolin.htm" target="_blank"]North Carolina Colleges and Universities[/url][/li]

[/list] ',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			4,
			'[br/]

[size="14"][b]Government:[/b][/size]

[list]

[li][url="http://www.ncga.state.nc.us" target="_blank"]State General Assembly[/url][/li]

[li][url="http://www.governor.state.nc.us" target="_blank"]Office of the Governor[/url][/li]

[li][url="http://www.ncgov.com" target="_blank"]North Carolina State Government[/url][br/]Link through to this site for hunting and fishing licenses, applying for college and financial aid, checking the unclaimed property database and many other services.[/li]

[li][url="http://www.ncdot.org/dmv/" target="_blank"]NCDOT Department of Motor Vehicles[/url][br/]NCDOT provides many online services like vehicle registration, and helpful information such as tips for preparing to take the driver\'s test.[/li]

[li][url="http://www2.ncocc.org/ncocc/homepage.htm" target="_blank"]North Carolina One Call Center[/url][br/]Contact this service before you dig. They will contact local public utilities who will mark the lines hidden in your yard. North Carolina statute requires that lines be marked prior to excavation. The phone number:1-800-632-4949 or 811.[/li]

[li][url="http://www.senate.gov" target="_blank"]U.S. Senate[/url][/li]

[li][url="http://www.house.gov" target="_blank"]U.S. House of Representatives[/url][/li]

[li][url="http://www.whitehouse.gov" target="_blank"]White House[/url][/li]

[/list] ',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			5,
			'[br/]

[size="14"][b]Parks:[/b][/size]

[list]

[li][url="http://www.apexnc.org/depts/parks/facilities/index.cfm#parks" target="_blank"]Apex Parks[/url][/li]

[li][url="http://www.wakegov.com/parks/crowder/default.htm" target="_blank"]Crowder District Park[/url][/li]

[li][url="http://www.wakegov.com/parks/harrislake/default.htm" target="_blank"]Harris Lake County Park[/url][/li]

[li][url="http://www.wakegov.com/parks/att/default.htm" target="_blank"]Americian Tobacco Trail[/url][/li]

[/list]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			6,
			'[br/]

[size="14"][b]Recreation & Sports:[/b][/size]

[list]

[li][url="http://www.caneshockey.com" target="_blank"]Carolina Hurricanes (NHL)[/url][/li]

[li][url="http://www.panthers.com" target="_blank"]Carolina Panthers (NFL)[/url][/li]

[li][url="http://www.nba.com/bobcats/" target="_blank"]Charlotte Bobcats (NBA)[/url][/li]

[li][url="http://www.wnba.com/sting/" target="_blank"]Charlotte Sting (WNBA)[/url][/li]

[li][url="http://www.carolinarailhawks.com" target="_blank"]Carolina Railhawks (NASL)[/url][/li][br/]

[li][url="http://www.dbulls.com" target="_blank"]Durham Bulls (IL)[/url][/li]

[li][url="http://www.milb.com/index.jsp?sid=t494" target="_blank"]Charlotte Knights (IL)[/url][/li][br/]

[li][url="http://www.gomudcats.com/default.asp" target="_blank"]North Carolina Mudcats (SL)[/url][/li][br/]

[li][url="http://www.fireantzhockey.com/default.aspx" target="_blank"]Fayeteville Fire Antz (SPHL)[/url][/li][br/]

[li][url="http://www.goduke.com" target="_blank]Duke Blue Devils[/url][/li]

[li][url="http://www.gopack.com" target="_blank]North Carolina Wolfpack[/url][/li]

[li][url="http://www.tarheelblue.com" target="_blank"]North Carolina Tar Heels[/url][/li]

[li][url="http://www.gocamels.com/landing/index" target="_blank"]Campbell University Camels[/url][/li]

[/list]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			7,
			'[br/]

[size="14"][b]Museums & Zoos:[/b][/size]

[list]

[li][url="http://ncmuseumofhistory.org" target="_blank"]North Carolina Museum of History[/url][/li]

[li][url="http://naturalsciences.org" target="_blank"]North Carolina State Museum of Natural Sciences[/url][/li]

[li][url="http://www.nczoo.org" target="_blank"]North Carolina Zoo[/url][/li]

[li][url="http://www.ncmls.org" target="_blank"]North Carolina Museum of Life & Science[/url][/li]

[/list]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			8,
			'[br/]

[size="14"][b]Arts & Culture:[/b][/size]

[list]

[li][url="http://ncartmuseum.org" target="_blank"]North Carolina Museum of Art[/url][/li]

[li][url="http://www.apexnc.org/depts/parks/artsCenter.cfm" target="_blank"]The Halle Cultural Arts Center[/url] in downtown Apex[/li]

[/list] ',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			9,
			'[br/]

[size="14"][b]News & Weather:[/b][/size]

[list]

[li][url="http://www.nhc.noaa.gov" target="_blank"]National Hurricane Center[/url][/li]

[li][url="http://www.erh.noaa.gov" target="_blank"]National Weather Service - Eastern Region[/url][/li]

[li][url="http://triangleweather.com/weather/hw3.php" target="_blank"]Triangle Weather[/url][/li]

[/list] ',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_community',
			10,
			'[br/]

[size="14"][b]Local Restaurants (partial list):[/b][/size]

[list]

[li][url="http://www.papajohns.com/" target="_blank"]Papa John\'s Pizza[/url][/li]

[li][url="http://www.zaxbys.com/locator/StoreMap.aspx?id=35602%20&locLat=35.42098&locLon=-78.800924" target="_blank"]Zaxby\'s[/url][/li]

[/list]',
			true
		);
		$manager->persist($content);

		$content = $this->build(
			'_documents',
			1,
			'[br/]

[size="14"][b]Local Restaurants (partial list):[/b][/size]

[list]

[li][url="http://www.papajohns.com/" target="_blank"]Papa John\'s Pizza[/url][/li]

[li][url="http://www.zaxbys.com/locator/StoreMap.aspx?id=35602%20&locLat=35.42098&locLon=-78.800924" target="_blank"]Zaxby\'s[/url][/li]

[/list]',
			true
		);
		$manager->persist($content);


		$manager->flush();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 2; // the order in which fixtures will be loaded
	}
}