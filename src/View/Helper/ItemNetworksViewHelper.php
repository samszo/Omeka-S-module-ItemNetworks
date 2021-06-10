<?php
namespace ItemNetworks\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\Filter\RealPath;
use \Datetime;

class ItemNetworksViewHelper extends AbstractHelper
{
    protected $api;
    protected $conn;
    protected $logger;
    protected $services;
    protected $entityManager;   

    var $rs;
    var $doublons;
    var $nivMax=1;//profondeur de la recherche
    var $reseau;
    var $item;
    //pour éviter un réseau trop grand on exclut des relations
    var $excluRela = ['skos:semanticRelation','cito:isCompiledBy'];
    
    public function __construct($services)
    {
      $this->api = $services['api'];
      $this->conn = $services['conn'];
      $this->logger = $services['logger'];
      $this->entityManager = $services['entityManager'];      

    }


    /**
     * Récupère le réseau conceptuel d'un item
     *
     * @param o:item                $item       item omeka
     * @param int                   $nivMax     pronfondeur du reseau
     * 
     * @return array
     */
    public function __invoke($item, $nivMax=false)
    {
        if(!$item) return [];
        if($nivMax)$this->nivMax=$nivMax;
        $this->item = $item;
        
        $this->reseau = ['nodes'=>[],'links'=>[]];

        $this->addItem($item,0);

        return $this->reseau;

    }
    
	/**
     * Ajout un item au reseau
     *
     * @param  o:item   $item
     * @param  int      $niv
     *
     * @return int
     */
    function addItem($item, $niv){

      /*construction de la réponse pour un affichage réseau
        {
          "nodes": [
              {
              "id": "Myriel",
              "group": 1,
              "size" : 10
              },
              {
              "id": "Napoleon",
              "group": 1
              },
          ],
          "links": [
              {
              "source": "Napoleon",
              "target": "Myriel",
              "value": 1
              },
              {
              "source": "Mlle.Baptistine",
              "target": "Myriel",
              "value": 8
              },
          ]
        };
       */
      //ajoute l'item
      if(!$this->addNoeud($item)) return;

      //ajoute les liens de l'item
      $relations = $item->subjectValues();
      foreach ($relations as $k => $r) {
        foreach ($r as $v) {
          $vr = $v->resource();
          $this->reseau['links'][] = ["target"=>$vr->id(),"source"=>$item->id(), "value"=>1, "group"=>$k];
          if($niv < $this->nivMax)
            $this->addItem($vr, $niv+1);
          else{
            $this->addNoeud($vr);
          }
        }
      }
      //ajoute les ressources de l'item
      $relations = $item->values();
      foreach ($relations as $k => $r) {
        foreach ($r['values'] as $v) {
          if($v->type()=='resource'){
            $vr = $v->valueResource();
            $this->reseau['links'][] = ["target"=>$vr->id(),"source"=>$item->id(), "value"=>1, "group"=>$k];  
            if($niv < $this->nivMax && !in_array($k, $this->excluRela))
              $this->addItem($vr, $niv+1);
            else{
              $this->addNoeud($vr);
            }
          }
        }
      }
      //ajoute les target de l'annotation
      if($item->resourceClass() && $item->resourceClass()->label()=="Annotation"){
        $tgts = $item->targets();
        foreach ($tgts as $t) {
          $vals = $t->value('rdf:value',['all'=>true]);
          foreach ($vals as $v) {
            $rv = $v->valueResource();
            if($rv){
              $this->addNoeud($rv);            
              $this->reseau['links'][] = ["target"=>$rv->id(),"source"=>$item->id(), "value"=>1, "group"=>$item->value('oa:motivatedBy')->asHtml()];  
            }
          }
        }  
      }


    }

  	/**
     * Ajout un noeud dans le réseau
     *
     * @param  o:item   $n
     *
     * @return boolean
     */
    function addNoeud($n){
      if(!isset($this->doublons[$n->id()])){
        $group = $n->resourceClass() ? $n->resourceClass()->label() : "item";
        $this->reseau['nodes'][] = ["id"=>$n->id(),"size"=>1,"group"=>$group,"title"=>$n->displayTitle()];  
        $this->doublons[$n->id()]=count($this->reseau['nodes'])-1;
        return true;
      }else{
        $this->reseau['nodes'][$this->doublons[$n->id()]]["size"]++;      
        return false;
      }
    }    

}
