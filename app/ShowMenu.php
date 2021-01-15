<?php
namespace App;

use Cache;
use App\Menu;
use Illuminate\Support\Facades\Auth;

class ShowMenu
{
  protected $items;

  public function __construct(){

  }

  public static function menu(){
    $menu = new ShowMenu();
    return $menu;
  }

  public function get($idParent=null){
    return Menu::where('parent_id','=',$idParent)->get();
  }

  public function render(){
    $html = "";

    $listMenu = $this->get();

    foreach($listMenu as $menu){
      if(auth()->user()->can($menu->permission->name)){
        $html .= $this->getHtmlMenu($menu);
        $listSubMenu = $this->get($menu->id);
        foreach($listSubMenu as $subMenu){
          if(auth()->user()->can($subMenu->permission->name)){
            $html .= $this->getHtmlMenu($subMenu);
            $listSubSubMenu = $this->get($subMenu->id);
            foreach($listSubSubMenu as $subSubMenu){
              if(auth()->user()->can($subSubMenu->permission->name)){
                $html .= $this->getHtmlMenu($subSubMenu);
              }
            }
            if ($subMenu->parent_status == 'Y') {
              $html.='   </ul></li>';
            }
          }
        }
        if ($menu->parent_status == 'Y') {
          $html.='   </ul></li>';
        }
      }
    }

    return $html;
  }

  // public function getHtmlMenu($menu){
  //   $hasSub = ($menu->parent_status == 'Y')?'has-sub':'';
  //   $menuUrl = ($menu->parent_status == 'Y')?'javascript:;':url($menu->url);
  //   $html ='<li class="site-menu-item '.$hasSub.'">
  //   <a href="'.$menuUrl.'">';
  //   if ($menu->ordinal == 1) {
  //     $html .='<i class="site-menu-icon '.$menu->icon.'" aria-hidden="true"></i>';
  //   }
  //   $html .='
  //     <span class="site-menu-title">'.$menu->name.'</span>';
  //   if ($menu->parent_status == 'Y') {
  //     $html .= '<span class="site-menu-arrow"></span>';
  //   }
  //   $html .='</a>';
  //   if ($menu->parent_status == 'Y') {
  //     $html .= '<ul class="site-menu-sub">';
  //   }

  //   return $html;
  // }

  public function getHtmlMenu($menu){
    $hasSub = ($menu->parent_status == 'Y')?'class="nav-submenu" data-toggle="nav-submenu"':'';
    $menuUrl = ($menu->parent_status == 'Y')?'#':url($menu->url);

    if($menu->parent_status == 'Y') {
      $html ='<li><a href="'.$menuUrl.'" '.$hasSub.'>';
    }
    else
    {
      $html ='<li><a href="'.$menuUrl.'">';
    }
    
    if ($menu->ordinal == 1) {
      $html .='<i class="si '.$menu->icon.'"></i>';
    }
    // $html .=__($menu->name);
    if ($menu->parent_status == 'Y' || $menu->parent_status == 'N') {
      $html .= '<span data-i18n="" class="menu-title">'.$menu->name.'</span>';

    //   if ($menu->ordinal == 1) {
    //   $html .='<i class="fa fa-angle-left pull-right"></i>';
    // }
    }
    $html .='</a>';
    if ($menu->parent_status == 'Y') {
      $html .= '<ul class="menu-content">';
    }

    return $html;
  }

  public function setCache(){
    Cache::put('user_menu_'.Auth::id(),$this->render(),120);
  }

  public function getCache(){
    $value = Cache::remember('user_menu_'.Auth::id(),120, function(){
      return $this->render();
    });

    return $value;
  }

  public function clearCache(){
    Cache::forget('user_menu_'.Auth::id());
  }

}
