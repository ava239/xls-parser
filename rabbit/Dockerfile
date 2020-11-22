FROM rabbitmq

ADD definitions.json /etc/rabbitmq/

RUN chown rabbitmq:rabbitmq /etc/rabbitmq/definitions.json

CMD ["rabbitmq-server"]
