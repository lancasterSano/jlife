<?php

Yii::import('zii.widgets.CMenu');
class jLSublMenu extends CMenu
{
	/**
	 * items subl menu
	 */
	public $items_subl=array();



	public function init()
	{
		if(isset($this->htmlOptions['id']))
			$this->id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$this->id;
		$route=$this->getController()->getRoute();

		// if(is_array($this->items) && count($this->items)){
			// $this->localDumperURL();
			$this->items=$this->normalizeItems($this->items,$route,$hasActiveChild);			
		// }
		// echo Yii::trace(CVarDumper::dumpAsString($this->items),'__items_subl');
	}

	/**
	 * Calls {@link renderMenu} to render the menu.
	 */
	public function run()
	{
		// echo Yii::trace(CVarDumper::dumpAsString($this->items),'__items_subl');
		$this->renderMenu($this->items);
	}

	protected function renderMenu($items)
	{
		if(count($items))
		{

			echo CHtml::openTag('div',array('class'=>'subl'))."\n";
				echo CHtml::openTag('ul',$this->htmlOptions)."\n";
				$this->renderMenuRecursive($items);
				echo CHtml::closeTag('ul');
			echo CHtml::closeTag('div');

			// subl menu render
			if(isset($this->items_subl) && !empty($this->items_subl)) {
				echo CHtml::openTag('div',array('class'=>'subl'))."\n";
					echo CHtml::openTag('ul',$this->htmlOptions)."\n";
					$this->renderMenuRecursive($this->items_subl);
					echo CHtml::closeTag('ul');
				echo CHtml::closeTag('div');
			}
		}
	}

	protected function renderMenuRecursive($items, $inItems=false)
	{
		$count=0;
		$n=count($items);
		foreach($items as $item)
		{
			$count++;
			$options=isset($item['itemOptions']) ? $item['itemOptions'] : array();
			$class=array();
			if($item['active'] && $this->activeCssClass!='')
				$class[]=$this->activeCssClass;
			if($count===1 && $this->firstItemCssClass!==null)
				$class[]=$this->firstItemCssClass;
			if($count===$n && $this->lastItemCssClass!==null)
				$class[]=$this->lastItemCssClass;
			if($this->itemCssClass!==null)
				$class[]=$this->itemCssClass;
			if($class!==array())
			{
				if(empty($options['class']))
					$options['class']=implode(' ',$class);
				else
					$options['class'].=' '.implode(' ',$class);
			}

			echo CHtml::openTag('li', $options);

			if($item['active'] && isset($item['items']) && count($item['items']))
			{
					// **** item with items
					echo CHtml::openTag('div', array('class'=>'extendMenu'));
						$menu=$this->renderMenuItem($item, false);
						if(isset($this->itemTemplate) || isset($item['template']))
						{
							$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
							echo strtr($template,array('{menu}'=>$menu));
						}
						else
							echo $menu;
						// рекурсивно подменю
						echo "\n".CHtml::openTag('ul',isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions)."\n";
						$this->renderMenuRecursive($item['items'], true);
						echo CHtml::closeTag('ul')."\n";
					echo CHtml::closeTag('div')."\n";			
			}
			else {
				$menu=$this->renderMenuItem($item, $inItems);
				if(isset($this->itemTemplate) || isset($item['template']))
				{
					$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
					echo strtr($template,array('{menu}'=>$menu));
				}
				else
					echo $menu;			
			}

			// <div class="extendMenu">
			//   <span>Средняя школа №51 П</span>
			//   <ul>
			//     <li><a href="">Преподаватель</a></li>
			//     <li><span class="active" >Ученик</span></li>
			//     <li><a href="">Родитель</a></li>
			//   </ul>  
			// </div>

			echo CHtml::closeTag('li')."\n";
		}
	}

	protected function renderMenuItem($item, $inItems)
	{
		if( 
			(!$inItems && isset($item['url']) && isset($item['items']) && count($item['items']) && $item['active']) ||
			$inItems && isset($item['url']) && $item['active']
		)
		return CHtml::tag('span',isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
		else
		{
			// exist in url absolute part
			if(stripos($item['url'][0], '..'))
			{
				// throw new Exception("Error Processing Request", 1);
				$url = Yii::app()->getBaseUrl(true).$item['url'][0];
				if(count($item['url'])>1) $url = $url.'?';
				foreach (array_splice($item['url'],1) as $key => $value) {
					$url = $url.$key.'='.$value.'&';
				}
				$url = rtrim($url,'&');
				
				// old code redirect
				$label=$this->linkLabelWrapper===null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
				return CHtml::link($label,$url,isset($item['linkOptions']) ? $item['linkOptions'] : array());
			}
			else
			{
				// new code refirect
				$label=$this->linkLabelWrapper===null ? $item['label'] : CHtml::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
				return CHtml::link($label,$item['url'],isset($item['linkOptions']) ? $item['linkOptions'] : array());
			}
		}
	}

	protected function normalizeItems($itemsroot,$route,&$active,$sublitems=null,$issublitems=false)
	{
		if($issublitems) $items = $sublitems;
		else $items = $itemsroot;

		foreach($items as $i=>$item)
		{
			// echo Yii::trace(,'__level_normalizeItems');
			// echo Yii::trace( 
			// 	CVarDumper::dumpAsString( $item['label'] ).
			// 	" [ items = ".CVarDumper::dumpAsString( isset($item['items']) )." ]".
			// 	" [ sublitems = ".CVarDumper::dumpAsString( isset($item['sublitems']) )." ]".
			// 	" [ initactive = ".CVarDumper::dumpAsString( isset($item['initactive']) )." ]",
			// 	'__level_normalizeItems'
			// );

			// удаляет visible=false, set null label if isnotset, encode label item
			if(isset($item['visible']) && !$item['visible']) { unset($items[$i]); continue; }
			if(!isset($item['label'])) $item['label']='';
			if($this->encodeLabel) $items[$i]['label']=CHtml::encode($item['label']);
			$hasActiveChild=false;

			if(isset($item['items']) && count($item['items'] && !$issublitems))
			{
				// выполнить нормализацию для всех items
				// old code ... нормализация, create label item if not has url, hide items where items is empty, 
				$items[$i]['items']=$this->normalizeItems($item['items'],$route,$hasActiveChild);
				if(empty($items[$i]['items']) && $this->hideEmptyItems)
				{
					unset($items[$i]['items']);
					if(!isset($item['url']))
					{
						unset($items[$i]);
						continue;
					}
				}
			}
			else if(isset($item['sublitems']) && count($item['sublitems']))
			{
				// isActive найти у subitems
					if($hasActiveChild)
					{
						// если уже найден активный дочерний, все sublitems можно удалить!!!!!!
						unset($items[$i]['sublitems']);
					}
					else
					{
						// old code ... нормализация, create label item if not has url, hide items where items is empty,
						$items[$i]['sublitems']=$this->normalizeItems(null,$route,$hasActiveChild,$item['sublitems'],true);
						if(empty($items[$i]['sublitems']) && $this->hideEmptyItems)
						{
							unset($items[$i]['sublitems']);
							if(!isset($item['url']))
							{
								unset($items[$i]);
								continue;
							}
						} else {
							if($hasActiveChild)
							{
								// если только нашли в тек sublitems => перенести
								$this->items_subl = $items[$i]['sublitems'];
							}
							// найден активный дочерний или нет все равно можно все sublitems удалить!!!!!!
							unset($items[$i]['sublitems']);							
						}
					}
			}

			if(!isset($item['active']))
			{
				if($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item,$route))
				{
					$active=$items[$i]['active']=true;
				}
				else
					$items[$i]['active']=false;
			}
			elseif($item['active'])
				$active=true;
		}
		return array_values($items);
	}

	private function localDumperItemURL($key, $item, $level=0) {
		if(($level*10)<=70) $color = '#f2e5fc';
		else if(($level*10)>=80 && $level<200) $color = 'none';
		else if(($level*10)>=200) $color = 'none';

		$route=$this->getController()->getRoute();


		$url_orig = $item['url'];
		$url = '';
		if( is_array($url_orig)  ){
			$url .= '<span>URL </span>';
			foreach ($url_orig as $key => $value) {
				if(!is_int($key))
					$url = $url.' '.$key;
					$url = $url.'=<b style="font-weight: 800;">'.$value.'</b>';					
			}
			// $url .= '</br>';
			echo Yii::trace(
				'<span style="background-color:'.$color.'; margin-left:'.($level*10).'px;">'.
					'<span style=" min-width:160px;margin: 0 0 0 '.($level*10).'px;">'.$item['label'].'</span>'.$url.
					'</br><tt style=" min-width:180px;margin: 0 0 0 '. ($level*14).'px;">Is Array: <b style="font-weight: 800;">'.is_array($item['url']).'</tt>'.
					'</br><tt style=" min-width:180px;margin: 0 0 0 '. ($level*14).'px;">Item URL[0]: <b style="font-weight: 800;">'.trim($item['url'][0],'/').'</tt>'.
					'</br><tt style=" min-width:180px;margin: 0 0 0 '. ($level*14).'px;">Compare: <b style="font-weight: 800;">'.!(strcasecmp(trim($item['url'][0],'/'),$route)).'</tt>'.
					'</br><tt style=" min-width:180px;margin: 0 0 0 '. ($level*14).'px;">IF <b style="font-weight: 800;">'. ((isset($item['url']) && is_array($item['url']) && !strcasecmp(trim($item['url'][0],'/'),$route)) ? 'true' :'false'). '</tt>'.
				'</span>',
				'___'.$this->id.'___'.((strcasecmp(trim($item['url'][0],'/'),$route) === 0) ? '<b><tt>Compare: true </tt></b>' : '')
			);
		}
		else
		{
			echo Yii::trace(
				'<span style="background-color:'.$color.'; min-width:180px;margin: 0 0 0 '.($level*10).'px;">'.$item['label'].'</span>'.
				'<span style="color:red">'.CVarDumper::dumpAsString($url_orig).'</span>'
				, '___'.$this->id.'___0'
			);
		}

		// if(is_array($item['items']) && count($item['items']))
		// {
		// 	foreach ($item['items'] as $key => $item) {
		// 		$this->localDumperItemURL($key, $item, ($level+4) );
		// 	}
		// }
	}
	private function localDumperURL() {
		if($this->id !== 'lineTabs') return;
		$route=$this->getController()->getRoute();
		echo Yii::trace(
			'<span style="min-width:180px;margin: 0 0 0 '.($level*10).'px;">'.'<b style="font-weight: 800;">CURRENT ROUTE</b>'.'</span>'.
			$route , 'CUR ROUTE'
		);
		foreach ($this->items as $key => $item) {									// All items menu
			$this->localDumperItemURL($key, $item, 1);
			if(is_array($item['sublitems']) && count($item['sublitems'])) {
				foreach ($item['sublitems'] as $key => $sublitem) {					// sublitems of menu item
					if(is_array($sublitem['items']) && count($sublitem['items']))
					{
						foreach ($sublitem['items'] as $key => $sublitem) {			// items of item from sublitems
							$this->localDumperItemURL($key, $sublitem, 6);
						}
					}
					else 
						$this->localDumperItemURL($key, $sublitem, 4);
				}
			}
			if(is_array($item['items']) && count($item['items']))
			{
				foreach ($item['items'] as $key => $item) {
					$this->localDumperItemURL($key, $item, 10);
				}
			}
		}
	}

	protected function isItemActive($item,$route)
	{
		if ($this->controller instanceof JLIFEDoUSMF) {
			$rez = null;
			$find_route = trim($item['url'][0],'/');
			if(isset($item['url']) && is_array($item['url']) && !substr_compare ($route, $find_route, 0, mb_strlen($find_route)))
			{
				unset($item['url']['#']);
				if(count($item['url'])>1)
				{
					foreach(array_splice($item['url'],1) as $name=>$value)
					{
						if(!isset($_GET[$name]) || $_GET[$name]!=$value) // return false;
							$rez = false;
					}
				} // return true;
				if($rez===null) $rez = true;
			} // return false;
			if($rez===null) $rez = false;
			if($rez) {
				$this->localDumperItemURL('null', $item, 7);
			}
			return $rez;
		}

		return parent::isItemActive($item, $route);
	}
}