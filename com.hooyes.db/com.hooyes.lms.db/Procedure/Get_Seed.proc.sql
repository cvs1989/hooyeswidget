DROP PROC [Get_Seed]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-02-02
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Seed]
	@ID int = 1, 
	@Value int output
AS
	UPDATE Seed SET Value=Value+1 WHERE ID = @ID
    SELECT @Value = Value FROM Seed WHERE ID = @ID
RETURN 0