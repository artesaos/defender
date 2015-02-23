<?php

namespace spec\Artisans\Guardian;

use Illuminate\Contracts\Foundation\Application;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuardianSpec extends ObjectBehavior
{

	function it_is_initializable(Application $app)
	{
		$this->beConstructedWith($app);
		$this->shouldHaveType('Artisans\Guardian\Guardian');
	}

}
