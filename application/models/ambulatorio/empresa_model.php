<?php

class empresa_model extends Model {

    var $_empresa_id = null;
    var $_nome = null;
    var $_razao_social = null;
    var $_cnpj = null;
    var $_celular = null;
    var $_telefone = null;
    var $_tipo_logradouro_id = null;
    var $_logradouro = null;
    var $_numero = null;
    var $_bairro = null;
    var $_complemento = null;
    var $_municipio_id = null;
    var $_cep = null;
    var $_cnes = null;

    function Empresa_model($exame_empresa_id = null) {
        parent::Model();
        if (isset($exame_empresa_id)) {
            $this->instanciar($exame_empresa_id);
        }
    }

    function listar($args = array()) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('empresa_id,
                            nome,
                            razao_social,
                            cnpj');
        $this->db->from('tb_empresa');
        $this->db->where('empresa_id', $empresa_id);
        if (isset($args['nome']) && strlen($args['nome']) > 0) {
            $this->db->where('nome ilike', $args['nome'] . "%");
        }
        return $this->db;
    }

    function pacotesms() {

        $this->db->select('descricao_pacote, pacote_sms_id');
        $this->db->from('tb_pacote_sms');
        $this->db->where('ativo', 't');
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresas() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_empresa_id,
                            nome, tipo');
        $this->db->from('tb_exame_empresa');
        $this->db->orderby('nome');
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function listarinformacaosms() {
        $empresa_id = $this->session->userdata('empresa_id');
        
        $this->db->select('pacote_id,
                            empresa_sms_id,
                            mensagem_confirmacao, 
                            mensagem_agradecimento,
                            mensagem_aniversariante');
        $this->db->from('tb_empresa_sms');
        $this->db->where('empresa_id', $empresa_id);
        $this->db->where('ativo', 't');
        $this->db->limit(1);
        $return = $this->db->get();
        return $return->result();
    }

    function listarempresa($empresa_id) {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('exame_empresa_id,
                            nome, tipo');
        $this->db->from('tb_exame_empresa');
        $this->db->where('exame_empresa_id', $empresa_id);
        $this->db->where('empresa_id', $empresa_id);
        $return = $this->db->get();
        return $return->result();
    }

    function excluir($exame_empresa_id) {

        $horario = date("Y-m-d H:i:s");
        $operador_id = $this->session->userdata('operador_id');
        $this->db->set('ativo', 'f');
        $this->db->set('data_atualizacao', $horario);
        $this->db->set('operador_atualizacao', $operador_id);
        $this->db->where('exame_empresa_id', $exame_empresa_id);
        $this->db->update('tb_exame_empresa');
        $erro = $this->db->_error_message();
        if (trim($erro) != "") // erro de banco
            return false;
        else
            return true;
    }

    function gravarconfiguracaosms() {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('empresa_id', $_POST['empresa_id']);
            $this->db->set('pacote_id', $_POST['txtpacote']);
            $this->db->set('mensagem_confirmacao', $_POST['txtMensagemConfirmacao']);
            $this->db->set('mensagem_agradecimento', $_POST['txtMensagemAgradecimento']);
            $this->db->set('mensagem_aniversariante', $_POST['txtMensagemAniversariantes']);

            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['sms_id'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa_sms');
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $sms_id = $_POST['sms_id'];
                $this->db->where('empresa_sms_id', $sms_id);
                $this->db->update('tb_empresa_sms');
            }
            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function gravar() {
        try {
            /* inicia o mapeamento no banco */
            $this->db->set('nome', $_POST['txtNome']);
            $this->db->set('razao_social', $_POST['txtrazaosocial']);
            $this->db->set('razao_socialxml', $_POST['txtrazaosocialxml']);
            $this->db->set('cep', $_POST['CEP']);
            $this->db->set('cnes', $_POST['txtCNES']);
            $this->db->set('email', $_POST['email']);
            if ($_POST['txtCNPJ'] != '') {
                $this->db->set('cnpj', str_replace("-", "", str_replace("/", "", str_replace(".", "", $_POST['txtCNPJ']))));
            }
            if ($_POST['txtCNPJxml'] != '') {
                $this->db->set('cnpjxml', str_replace("-", "", str_replace("/", "", str_replace(".", "", $_POST['txtCNPJxml']))));
            }
            $this->db->set('telefone', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['telefone']))));
            $this->db->set('celular', str_replace("(", "", str_replace(")", "", str_replace("-", "", $_POST['celular']))));
            if ($_POST['municipio_id'] != '') {
                $this->db->set('municipio_id', $_POST['municipio_id']);
            }
            $this->db->set('logradouro', $_POST['endereco']);
            $this->db->set('numero', $_POST['numero']);
            $this->db->set('bairro', $_POST['bairro']);



            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            if ($_POST['txtempresaid'] == "") {// insert
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_empresa');
                $erro = $this->db->_error_message();
                if (trim($erro) != "") // erro de banco
                    return -1;
                else
                    $empresa_id = $this->db->insert_id();
            }
            else { // update
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $empresa_id = $_POST['txtempresaid'];
                $this->db->where('empresa_id', $empresa_id);
                $this->db->update('tb_empresa');
            }
            return $empresa_id;
        } catch (Exception $exc) {
            return -1;
        }
    }

    function carregarlistarpostsblog($posts_blog_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('posts_blog_id, titulo, corpo_html, data_cadastro');
        $this->db->from('tb_posts_blog e');
        // $this->db->where('e.ativo', 't');
        $this->db->where('posts_blog_id', $posts_blog_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    function listarpostsblog() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('posts_blog_id, titulo, corpo_html, data_cadastro');
        $this->db->from('tb_posts_blog e');
        $this->db->where('e.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function gravarpostsblog() {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            $this->db->set('titulo', $_POST['titulo']);
            $this->db->set('corpo_html', $_POST['texto']);
            // $this->db->set('empresa_id', $empresa_id);

//            var_dump($_POST); die;
            if (!$_POST['posts_blog_id'] > 0) {
                
                $this->db->set('data_cadastro', $horario);
                $this->db->set('operador_cadastro', $operador_id);
                $this->db->insert('tb_posts_blog');
            } else {
                $this->db->set('data_atualizacao', $horario);
                $this->db->set('operador_atualizacao', $operador_id);
                $this->db->where('posts_blog_id', $_POST['posts_blog_id']);
                $this->db->update('tb_posts_blog');
            }

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function excluirpostsblog($post_id) {
        try {
//            var_dump($_POST['impressao_id']); die;
            /* inicia o mapeamento no banco */
            $empresa_id = $this->session->userdata('empresa_id');
            $horario = date("Y-m-d H:i:s");
            $operador_id = $this->session->userdata('operador_id');

            
            
            $this->db->set('ativo', 'f');
            $this->db->set('data_atualizacao', $horario);
            $this->db->set('operador_atualizacao', $operador_id);
            $this->db->where('posts_blog_id', $post_id);
            $this->db->update('tb_posts_blog');
            

            $erro = $this->db->_error_message();
            if (trim($erro) != "") // erro de banco
                return -1;
            else
//                $ambulatorio_guia_id = $this->db->insert_id();
                return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    function listarpesquisasatisfacao() {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pp.paciente_pesquisa_satisfacao_id, pp.paciente_id, p.nome as paciente, pp.questionario, pp.data_cadastro');
        $this->db->from('tb_paciente_pesquisa_satisfacao pp');
        $this->db->join('tb_paciente p', 'p.paciente_id = pp.paciente_id', 'left');
        $this->db->where('pp.ativo', 't');
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        return $this->db;
    }

    function detalhespesquisasatisfacao($pesquisa_id) {
        $data = date("Y-m-d");
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->select('pp.paciente_pesquisa_satisfacao_id, pp.paciente_id, p.nome as paciente, pp.questionario, pp.data_cadastro');
        $this->db->from('tb_paciente_pesquisa_satisfacao pp');
        $this->db->join('tb_paciente p', 'p.paciente_id = pp.paciente_id', 'left');
        $this->db->where('pp.paciente_pesquisa_satisfacao_id', $pesquisa_id);
//        $this->db->where('paciente_id', $paciente_id);
//        $this->db->where('data_criacao', $data);
        $return = $this->db->get();
        return $return->result();
    }

    private function instanciar($empresa_id) {

        if ($empresa_id != 0) {
            $this->db->select('empresa_id, 
                               f.nome,
                               razao_social,
                               cnpj,
                               celular,
                               telefone,
                               email,
                               cep,
                               logradouro,
                               numero,
                               bairro,
                               cnes,
                               f.municipio_id,
                               c.nome as municipio,
                               c.estado,
                               cep');
            $this->db->from('tb_empresa f');
            $this->db->join('tb_municipio c', 'c.municipio_id = f.municipio_id', 'left');
            $this->db->where("empresa_id", $empresa_id);
            $query = $this->db->get();
            $return = $query->result();
            $this->_empresa_id = $empresa_id;
            $this->_nome = $return[0]->nome;
            $this->_cnpj = $return[0]->cnpj;
            $this->_razao_social = $return[0]->razao_social;
            $this->_celular = $return[0]->celular;
            $this->_telefone = $return[0]->telefone;
            $this->_email = $return[0]->email;
            $this->_cep = $return[0]->cep;
            $this->_logradouro = $return[0]->logradouro;
            $this->_numero = $return[0]->numero;
            $this->_bairro = $return[0]->bairro;
            $this->_municipio_id = $return[0]->municipio_id;
            $this->_municipio = $return[0]->municipio;
            $this->_nome = $return[0]->nome;
            $this->_estado = $return[0]->estado;
            $this->_cep = $return[0]->cep;
            $this->_cnes = $return[0]->cnes;
        } else {
            $this->_empresa_id = null;
        }
    }

}

?>
