<?php
class EAnnotationArrayValuesMatcher extends EParallelMatcher
{

	protected function build()
	{
		$this->add(new EAnnotationArrayValueMatcher);
		$this->add(new EAnnotationMoreValuesMatcher);
	}
}