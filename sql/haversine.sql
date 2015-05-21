-- drop the procedure if already defined
DROP PROCEDURE IF EXISTS haversine;

-- procedure to calculate the distance between two points
-- @param POINT $origin point of origin
-- @param POINT $destination point heading out
-- @return DECIMAL distance between the points, in kilometers
CREATE PROCEDURE haversine(IN origin POINT, destination POINT)
	PROC:BEGIN
		-- first, declare all variables; I don't think you can declare later
		DECLARE radius DECIMAL(5, 1);
		DECLARE latitudeAngle1 DECIMAL(12, 9);
		DECLARE latitudeAngle2 DECIMAL(12, 9);
		DECLARE latitudePhase DECIMAL(12, 9);
		DECLARE longitudePhase DECIMAL(12, 9);
		DECLARE alpha DECIMAL(12, 9);
		DECLARE corner DECIMAL(12, 9);
		DECLARE distance DECIMAL(16, 9);

		-- assign the variables that were declared & use them
		SET radius = 6372.8; -- radius of the earth in kilometers
		SET latitudeAngle1 = RADIANS(X(origin));
		SET latitudeAngle2 = RADIANS(X(destination));
		SET latitudePhase = RADIANS(X(destination) - X(origin));
		SET longitudePhase = RADIANS(Y(destination) - Y(origin));

		SET alpha = SIN(latitudePhase / 2) * SIN(latitudePhase / 2)
			+ SIN(longitudePhase / 2) * SIN(longitudePhase / 2)
			* COS(latitudeAngle1) * COS(latitudeAngle2);
		SET corner = 2 * ASIN(SQRT(alpha));
		SET distance = radius * corner;

		-- what is "SELECT"ed here is what is returned
		SELECT distance;
	END;