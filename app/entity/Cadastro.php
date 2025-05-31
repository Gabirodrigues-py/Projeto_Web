<?php

namespace App\entity;

use \App\Db\Database;

class Cadastro{
   /**
    * Identificador unico do cadastro
    * @var INT
    */
    public $id;

    /**
     * Nome do Usuario
     * @var string
     */
    public $nome;

    /**
     * Sobrenome do Usuario
     * @var string
     * 
     */
    public $sobrenome;

    /**
     * e-mail do Usuario
     * @var string
     */
    public $email;

    /**
     * senha criptograda do usuario
     * @var string
     */
    public $senha;

    /**
     * telefone de contato do usuario
     * @var string 
     */
    public $telefone;

    /**
     * CPF
     * @var string
     */
    public $CPF; // Note: Property name is CPF (uppercase)

    /**
     * genero do usuario
     * @var string ('Masculino','Feminino','Outro')
     */
    public $sexo;

    /**
     * onde usuario reside
     * @var string
     */
    public $pais_residencia;

    /**
     * data da criação do usuario
     * @var string 
     */
    public $data;

    /**
     * Data de nascimento do usuario
     * @var string
     */
     public $data_de_nascimento; // Added based on previous code analysis

     /**
      *Método responsavel por cadastrar o usuario no banco 
      *@return boolean
      */
     public function cadastrar(){
        //DEFINIR A DATA
        $this->data = date('Y-m-d H:i:s');

        //INSERIR A VAGA NO BANDO
        $obDatabase = new Database ('usuarios');
        $this->id = $obDatabase->insert([
                              'nome'                =>$this->nome,
                              'sobrenome'           =>$this->sobrenome,
                              'email'               =>$this->email,
                              'senha'               =>$this->senha,
                              'telefone'            =>$this->telefone,
                              'CPF'                 =>$this->CPF,
                              'sexo'                =>$this->sexo,
                              'data'                =>$this->data,
                              'data_de_nascimento'  =>$this->data_de_nascimento                        
        ]);

        
      //RETORNAR SUCESSO
      return true;

     }


}
