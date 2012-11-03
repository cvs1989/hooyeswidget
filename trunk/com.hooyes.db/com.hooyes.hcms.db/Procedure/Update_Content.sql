DROP PROC [Update_Content]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-11-03
-- Update date: 2012-11-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Content]
	@CID INT = 0 output, 
    @Title NVARCHAR(100) , 
    @Content NVARCHAR(MAX) , 
    @Author NVARCHAR(50) = '',
    @CreateDate DATETIME = null, 
    @UpdateDate DATETIME = null
AS
	IF @CreateDate = null 
		SET @CreateDate = GETDATE()
	IF @UpdateDate = null
		SET @UpdateDate = GETDATE()

	IF EXISTS(
		SELECT 1 FROM Hcms_Content WHERE CID = @CID
	)
		BEGIN
			UPDATE Hcms_Content 
				SET Title = @Title,
					Content = @Content,
					UpdateDate = @UpdateDate,
					Author = @Author
			WHERE CID = @CID
				    
		END
	ELSE
		BEGIN
			EXECUTE [Get_Seed] 
			   @ID = 1
			  ,@Value = @CID OUTPUT

			INSERT INTO Hcms_Content(CID,Title,Content,Author,CreateDate,UpdateDate)
			VALUES (@CID,@Title,@Content,@Author,@CreateDate,@UpdateDate)
		END
RETURN 0
