<?php
    namespace Lenovo\Dalu\Interfaces;
    
    interface IUsuarios {
        public function insert();
        public function search();
        public function searchInactive();
        public function update();
        public function delete();
        public function active();
    }