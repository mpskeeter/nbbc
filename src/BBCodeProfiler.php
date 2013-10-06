<?php

	namespace MPeters\NbbcBundle\src;

	//-----------------------------------------------------------------------------
	//  This profiler class helps us to easily detect performance bottlenecks.
	//  We leave it out of the high-speed compressed version of NBBC for
	//  performance reasons; this is really a debugging aid more than anything.

	//<skip-when-compressing>
	class BBCodeProfiler {
		var $start_time, $total_times;

		function __construct()
		{ $this->start_time = Array(); $this->total_times = Array(); }

		function Now()
		{ list($usec, $sec) = explode(" ", microtime());
			return ((float)$usec + (float)$sec); }

		function Begin($group)
		{ $this->start_time[$group] = $this->Now(); }
		function End($group)
		{ $time = $this->Now() - $this->start_time[$group];
			if (!isset($this->total_times[$group]))
				$this->total_times[$group] = $time;
			else $this->total_times[$group] += $time; }
		function Reset($group)
		{ $this->total_times[$group] = 0; }
		function Total($group)
		{ return @$this->total_times[$group]; }

		function DumpAllGroups() {
			print "<div>Profiled times:\n<ul>\n";
			ksort($this->total_times);
			foreach ($this->total_times as $name => $time) {
				print "<li><b>" . htmlspecialchars($name) . "</b>: " . sprintf("%0.2f msec", $time * 1000) . "</li>\n";
			}
			print "</ul>\n</div>\n";
		}
	}
	//</skip-when-compressing>
