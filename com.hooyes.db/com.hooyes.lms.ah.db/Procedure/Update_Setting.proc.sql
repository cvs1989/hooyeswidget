-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-03
-- Update date: 2012-02-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Setting]
	 @MID int
	 ,@Type int  = -1
	 ,@Phone varchar(50)
AS
	UPDATE Member
	SET [Type] = @Type
		,Phone = @Phone
	WHERE MID = @MID
RETURN 0