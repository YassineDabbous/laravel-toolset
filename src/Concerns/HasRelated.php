<?php
namespace Ysn\SuperCore\Concerns;

trait HasRelated{

    //
    // suggestion depends on post/account type
    //

    public function related() : \Illuminate\Database\Eloquent\Builder {
        $wheres = [];
        $orWheres = [];

        // don't use a condition because
        // Account::PERSONAL == 0, PostModel::UNDEFINED == 0
        // Canceled:
        //    if type is not GENERAl => get same posts type
        //      if($this->type)
            $wheres['type'] = $this->type;


        if($this->account_id){ // because Account doens't have account_id column
            $orWheres['account_id'] = $this->account_id;
        }

        if($this->category_id){
            $orWheres['category_id'] = $this->category_id;
        }
        if($this->location_id){
            $orWheres['location_id'] = $this->location_id;
        }
        // if($this->country_id){
        //     $orWheres['country_id'] = $this->country_id;
        // }

        if($this->postable_type && $this->postable_id){
            $orWheres['postable'] = [
                'postable_type'  => $this->postable_type,
                'postable_id'    => $this->postable_id,
            ];
        }


        $q = $this->newQuery();
        $q->where('id', '!=', $this->id);
        if(count($wheres) > 0){
            $q->where($wheres);
        }
        if(count($orWheres) > 0){
            $q->where(function($qq) use ($orWheres){
                foreach ($orWheres as $key => $value) {
                    if(is_array($value)){ //  ex: [ 'postable' => [] ]
                        $qq->orWhere(function($q) use($value) {
                            $q->where($value);
                        });
                    }else {
                        $qq->orWhere($key, $value);
                    }
                }
            });
        }
        return $q;
    }
}
