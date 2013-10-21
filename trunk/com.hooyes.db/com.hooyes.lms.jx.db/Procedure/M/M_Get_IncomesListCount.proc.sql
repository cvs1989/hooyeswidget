-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-10-19
-- Update date: 2013-10-19
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_IncomesListCount]
    @Filter VARCHAR(700) = ''
AS 
    IF @Filter = '' 
        SELECT  COUNT(1)
        FROM    v_m_incomes 
    ELSE 
        BEGIN
            DECLARE @SQL VARCHAR(800)
            SET @SQL = 'SELECT COUNT(1) FROM v_m_incomes WHERE ' + @Filter
            EXECUTE(@SQL)
        END
    RETURN 0