-- ===========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 03/05/2016
-- Descrição: Extrato de saldo das contas bancárias.
-- ===========================================================================================

IF OBJECT_ID ('vwExtratoDeSaldoDasContasBancarias', 'V') IS NOT NULL
DROP VIEW vwExtratoDeSaldoDasContasBancarias ;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW dbo.vwExtratoDeSaldoDasContasBancarias
AS
SELECT b.idPronac,b.AnoProjeto+b.Sequencial as Pronac,b.NomeProjeto,a.stContaLancamento,
       CASE
	     WHEN a.stContaLancamento = 0
		   THEN 'Captação'
		   ELSE 'Movimentação'
		 END Tipo,
       a.nrAgenciaSaldoBancario as Agencia,a.nrContaSaldoBancario as NrConta,
       CASE
	     WHEN a.siSaldoBancario = 0
		   THEN 'Saldo Inicial'
		   ELSE 'Saldo Final'
		 END AS TipoSaldo,
	   a.dtSaldoBancario,a.vlSaldoBancario,a.stSaldoBancario
FROM SAC.DBO.tbSaldoBancario a 
INNER JOIN SAC.dbo.Projetos  b on (a.idPronac = b.IdPRONAC)

GO 

GRANT  SELECT ON dbo.vwExtratoDeSaldoDasContasBancarias  TO usuarios_internet
GO
