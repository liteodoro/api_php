<?php

class ProdutoController
{
    public function getAll($ativo = 1, $pag = 1,  $limit_to = 12)
    {
        try {
            $sql = "SELECT * from produto where ativo = :ativo order by nome limit :limit_from,:limit_to ";
            $limit_from = ($pag * $limit_to) - $limit_to;
            $dao = new DAO;
            $conn = $dao->conecta();
            $stman = $conn->prepare($sql);
            $stman->bindParam(":ativo", $ativo);
            $stman->bindParam(":limit_from", $limit_from, PDO::PARAM_INT);
            $stman->bindParam(":limit_to", $limit_to, PDO::PARAM_INT);
            $stman->execute();
            $result = $stman->fetchAll();
            return $result;
        } catch (Exception $e) {
            throw new Exception("Erro ao listar os produto: " . $e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $sql = "SELECT * from produto where id = :id and ativo <> 0";
            $dao = new DAO;
            $stman = $dao->conecta()->prepare($sql);
            $stman->bindParam(":id", $id);
            $stman->execute();
            $result = $stman->fetchALL();
            return $result;
        } catch (Exception $e) {
            throw new Exception("Erro ao pegar o produto: " . $e->getMessage());
        }
    }

    public function add(Produto $produto)
    {
        try {
            $sql = "INSERT INTO produto (nome, descricao, quant, valor, largura, altura, comprimento, peso, fotos, ativo) 
                    VALUES
                    (:nome, :descricao, :quant, :valor, :largura, :altura, :comprimento, :peso, :fotos, :ativo)";
            $dao = new DAO;
            $stman = $dao->conecta()->prepare($sql);
            $stman->bindParam(":nome", $produto->nome);
            $stman->bindParam(":descricao", $produto->descricao);
            $stman->bindParam(":quant", $produto->quant);
            $stman->bindParam(":valor", $produto->valor);
            $stman->bindParam(":largura", $produto->largura);
            $stman->bindParam(":altura", $produto->altura);
            $stman->bindParam(":comprimento", $produto->comprimento);
            $stman->bindParam(":peso", $produto->peso);
            $stman->bindParam(":fotos", $produto->fotos);
            $stman->bindParam(":ativo", $produto->ativo);
            return $stman->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao cadastra o produto: " . $e->getMessage());
        }
    }

    public function update(produto $produto)
    {
        try {
            $sql = "UPDATE produto 
                    SET 
                        nome = :nome,
                        descricao = :descricao,
                        quant = :quant,
                        valor = :valor,
                        largura = :largura,
                        altura = :altura,
                        comprimento = :comprimento,
                        peso = :peso,
                        fotos = :fotos,
                        ativo = :ativo
                    WHERE produto.id = :id";
            //$senhaCryp = md5($produto->senha);
            $senhaCryp = crypt($produto->senha, '$5$rounds=5000$' . $produto->email . '$');
            $dataBanco = $this->formatDateBD($produto->data_nasc);

            $dao = new DAO;
            $stman = $dao->conecta()->prepare($sql);
            $stman->bindParam(":id", $produto->id);
            $stman->bindParam(":nome", $produto->nome);
            $stman->bindParam(":descricao", $produto->descricao);
            $stman->bindParam(":quant", $produto->quant);
            $stman->bindParam(":valor", $produto->valor);
            $stman->bindParam(":largura", $produto->largura);
            $stman->bindParam(":altura", $produto->altura);
            $stman->bindParam(":comprimento", $produto->comprimento);
            $stman->bindParam(":peso", $produto->peso);
            $stman->bindParam(":fotos", $produto->fotos);
            $stman->bindParam(":ativo)", $produto->ativo);
            return $stman->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizado o produto: " . $e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            //$sql = "DELETE FROM produto WHERE id = :id";
            $sql = "UPDATE produto Set ativo = 0 Where id = :id";
            $dao = new DAO;
            $stman = $dao->conecta()->prepare($sql);
            $stman->bindParam(":id", $id);
            return $stman->execute();
        } catch (PDOException $pe) {
            throw new Exception("Erro ao apagar o produto: " . $pe->getMessage());
        } catch (Exception $e) {
            throw new Exception("Erro ao acessar a base de dados: " . $e->getMessage());
        }
    }

    public function updatePhoto($id, $fotoName)
    {
        try {
            $sql = "UPDATE produto 
                    SET 
                    fotos = :fotos
                    WHERE produto.id = :id";
            $dao = new DAO;
            $stman = $dao->conecta()->prepare($sql);
            $stman->bindParam(":fotos", $fotoName);
            $stman->bindParam(":id", $id);
            $stman->execute();
            return $fotoName;
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizado a fotos do produto: " . $e->getMessage());
        }
    }


    private  function  formatDateBD($date)
    { // Entrada: DD/MM/YYYY -> YYYY/MM/DD
        $partDate = explode("/", $date);
        return ($partDate[2] . "-" . $partDate[1] . "-" . $partDate[0]);
    }
}